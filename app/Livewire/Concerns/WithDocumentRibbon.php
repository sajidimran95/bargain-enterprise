<?php

namespace App\Livewire\Concerns;

use App\Mail\DocumentMail;
use App\Models\Item;
use App\Services\DocumentPdfService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

trait WithDocumentRibbon
{
    use WithFileUploads;

    public ?int $navigatorId = null;

    public bool $showAttachModal = false;

    public bool $showTimeCostsModal = false;

    public bool $showEmailComposer = false;

    public string $emailTo = '';

    public string $emailSubject = '';

    /** @var array<int, TemporaryUploadedFile> */
    public array $pendingAttachments = [];

    public string $timeCostDescription = '';

    public string $timeCostAmount = '0.00';

    public string $timeCostQty = '1';

    public string $formattingFontSize = '12';

    public bool $formattingBold = false;

    abstract protected function documentModelClass(): string;

    abstract protected function documentNumberColumn(): string;

    abstract protected function documentPdfRouteName(): string;

    abstract protected function documentBatchListRouteName(): string;

    abstract protected function documentPdfView(): string;

    /**
     * @return array<string, mixed>
     */
    abstract protected function documentPdfData(Model $document): array;

    abstract protected function loadDocumentIntoForm(Model $document): void;

    abstract protected function saveForRibbon(): ?Model;

    public function findPreviousDocument(): void
    {
        $doc = $this->adjacentDocument('prev');
        if (! $doc) {
            $this->dispatch('be-toast', message: 'No previous document.');

            return;
        }

        $this->loadDocumentIntoForm($doc);
        $this->navigatorId = (int) $doc->getKey();
        $this->dispatch('be-toast', message: 'Loaded '.$this->documentLabel($doc).'. Save updates this document (stock and balance adjust).');
    }

    public function findNextDocument(): void
    {
        $doc = $this->adjacentDocument('next');
        if (! $doc) {
            $this->dispatch('be-toast', message: 'No next document.');

            return;
        }

        $this->loadDocumentIntoForm($doc);
        $this->navigatorId = (int) $doc->getKey();
        $this->dispatch('be-toast', message: 'Loaded '.$this->documentLabel($doc).'. Save updates this document (stock and balance adjust).');
    }

    public function printDocument(): void
    {
        $doc = $this->resolveSavedDocument();
        if ($doc) {
            $url = route($this->documentPdfRouteName(), $doc);
            $this->js('window.open('.json_encode($url).', "_blank")');
            $this->dispatch('be-toast', message: 'Opening PDF for '.$this->documentLabel($doc));

            return;
        }

        $this->js('window.print()');
        $this->dispatch('be-toast', message: 'Printing form. Save first for a PDF.');
    }

    public function emailDocument(): void
    {
        $doc = $this->resolveSavedDocument() ?? $this->saveForRibbon();
        if (! $doc) {
            return;
        }

        $doc->loadMissing('customer');
        $this->navigatorId = (int) $doc->getKey();
        $this->emailTo = (string) ($doc->customer?->email ?: auth()->user()?->email ?: '');
        $this->emailSubject = $this->documentLabel($doc);
        $this->showEmailComposer = true;
    }

    public function closeEmailComposer(): void
    {
        $this->showEmailComposer = false;
    }

    public function sendRibbonEmail(DocumentPdfService $pdf): void
    {
        $this->validate([
            'emailTo' => ['required', 'email'],
            'emailSubject' => ['required', 'string', 'max:255'],
        ]);

        $doc = $this->resolveSavedDocument();
        if (! $doc) {
            $this->dispatch('be-toast', message: 'Save the document before emailing.');

            return;
        }

        $doc->loadMissing(['customer', 'lines.item']);
        $content = $pdf->output($this->documentPdfView(), $this->documentPdfData($doc));
        $number = $this->documentLabel($doc);

        Mail::to($this->emailTo)->send(new DocumentMail(
            headline: $this->emailSubject,
            intro: 'Please find attached '.$number.' from '.config('bargain.company_name', config('app.name')).'.',
            pdf: [
                'filename' => str($number)->slug('-').'.pdf',
                'content' => $content,
            ],
        ));

        $this->showEmailComposer = false;
        $this->dispatch('be-toast', message: 'Emailed to '.$this->emailTo);
    }

    public function openAttachModal(): void
    {
        $this->showAttachModal = true;
    }

    public function closeAttachModal(): void
    {
        $this->showAttachModal = false;
    }

    public function removePendingAttachment(int $index): void
    {
        unset($this->pendingAttachments[$index]);
        $this->pendingAttachments = array_values($this->pendingAttachments);
    }

    public function confirmAttachments(): void
    {
        $this->validate([
            'pendingAttachments' => ['array', 'max:10'],
            'pendingAttachments.*' => ['file', 'max:10240'],
        ]);

        $this->showAttachModal = false;
        $count = count($this->pendingAttachments);
        $this->dispatch('be-toast', message: $count === 0
            ? 'No files attached.'
            : $count.' file(s) ready — stored when you Save.');
    }

    public function openTimeCostsModal(): void
    {
        $this->timeCostDescription = '';
        $this->timeCostAmount = '0.00';
        $this->timeCostQty = '1';
        $this->showTimeCostsModal = true;
    }

    public function closeTimeCostsModal(): void
    {
        $this->showTimeCostsModal = false;
    }

    public function applyTimeCosts(): void
    {
        $this->validate([
            'timeCostDescription' => ['required', 'string', 'max:255'],
            'timeCostAmount' => ['required', 'numeric', 'min:0'],
            'timeCostQty' => ['required', 'numeric', 'gt:0'],
        ]);

        $item = Item::query()->active()->orderBy('id')->first();
        if (! $item) {
            $this->dispatch('be-toast', message: 'Add an item in Item List before using Add Time/Costs.');

            return;
        }

        $qty = (float) $this->timeCostQty;
        $rate = (float) $this->timeCostAmount;
        $payload = [
            'item_id' => (string) $item->id,
            'item_code' => $item->barcode ?: $item->sku,
            'description' => $this->timeCostDescription,
            'quantity' => number_format($qty, 2, '.', ''),
            'rate' => number_format($rate, 2, '.', ''),
            'amount' => number_format($qty * $rate, 2, '.', ''),
            'taxable' => false,
            'class' => '',
        ];

        $filled = false;
        foreach ($this->lines as $index => $line) {
            if (blank($line['item_id'] ?? null) && blank($line['item_code'] ?? null)) {
                $payload['class'] = $this->lines[$index]['class'] ?? '';
                $this->lines[$index] = $payload;
                $filled = true;
                break;
            }
        }

        if (! $filled) {
            $this->addLine();
            $this->lines[count($this->lines) - 1] = $payload;
        }

        $this->showTimeCostsModal = false;
        $this->dispatch('be-toast', message: 'Time/cost line added.');
    }

    public function createBatch(): void
    {
        // QB Desktop: Create a Batch opens Print Forms for invoices marked Print/Email Later.
        $queue = 'print';
        $hasPrint = property_exists($this, 'print_later') && $this->print_later;
        $hasEmail = property_exists($this, 'email_later') && $this->email_later;

        if ($hasPrint && $hasEmail) {
            $queue = 'both';
        } elseif ($hasEmail) {
            $queue = 'email';
        } elseif (property_exists($this, 'print_later')) {
            $this->print_later = true;
        }

        $route = $this->documentBatchListRouteName();
        $this->js(
            'window.parent.postMessage({type:"be-workspace-open",route:'.json_encode($route)
            .',params:'.json_encode(['queue' => $queue]).'},"*")'
        );
        $this->dispatch('be-toast', message: 'Opening Create a Batch — select Print Later / Email Later invoices to print or email together.');
    }

    public function toggleFormattingBold(): void
    {
        $this->formattingBold = ! $this->formattingBold;
    }

    protected function adjacentDocument(string $direction): ?Model
    {
        /** @var class-string<Model> $class */
        $class = $this->documentModelClass();
        $cursor = $this->navigatorId;

        if ($cursor) {
            if ($direction === 'prev') {
                return $class::query()->where('id', '<', $cursor)->orderByDesc('id')->first();
            }

            return $class::query()->where('id', '>', $cursor)->orderBy('id')->first();
        }

        return $direction === 'prev'
            ? $class::query()->orderByDesc('id')->first()
            : $class::query()->orderBy('id')->first();
    }

    protected function resolveSavedDocument(): ?Model
    {
        if (! $this->navigatorId) {
            return null;
        }

        /** @var class-string<Model> $class */
        $class = $this->documentModelClass();

        return $class::query()->with('customer')->find($this->navigatorId);
    }

    protected function documentLabel(Model $document): string
    {
        $column = $this->documentNumberColumn();

        return (string) ($document->{$column} ?? $document->getKey());
    }

    protected function storePendingAttachmentsFor(string $folder): int
    {
        $stored = 0;
        foreach ($this->pendingAttachments as $file) {
            if (! $file instanceof TemporaryUploadedFile) {
                continue;
            }
            $file->storeAs($folder, $file->getClientOriginalName(), 'local');
            $stored++;
        }
        $this->pendingAttachments = [];

        return $stored;
    }
}
