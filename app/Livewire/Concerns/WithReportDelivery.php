<?php

namespace App\Livewire\Concerns;

use App\Mail\DocumentMail;
use App\Services\DocumentPdfService;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait WithReportDelivery
{
    public bool $showEmailModal = false;

    public string $emailTo = '';

    public string $emailSubject = '';

    abstract protected function reportPdfTitle(): string;

    abstract protected function reportPdfSubtitle(): ?string;

    abstract protected function reportPdfBodyHtml(): string;

    abstract protected function reportPdfFilename(): string;

    public function openEmailModal(): void
    {
        foreach (['showCommentModal', 'showShareModal', 'showMemorizeModal'] as $popup) {
            if (property_exists($this, $popup)) {
                $this->{$popup} = false;
            }
        }

        $this->showEmailModal = true;
        $this->emailTo = auth()->user()?->email ?? '';
        $subtitle = $this->reportPdfSubtitle();
        $this->emailSubject = $this->reportPdfTitle().($subtitle ? ' — '.$subtitle : '');
    }

    public function closeEmailModal(): void
    {
        $this->showEmailModal = false;
    }

    public function exportPdf(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);

        return app(DocumentPdfService::class)->streamDownload(
            'pdf.report',
            [
                'title' => $this->reportPdfTitle(),
                'subtitle' => $this->reportPdfSubtitle(),
                'bodyHtml' => $this->reportPdfBodyHtml(),
            ],
            $this->reportPdfFilename()
        );
    }

    public function sendReportEmail(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $this->validate([
            'emailTo' => ['required', 'email'],
            'emailSubject' => ['required', 'string', 'max:200'],
        ]);

        $content = app(DocumentPdfService::class)->output('pdf.report', [
            'title' => $this->reportPdfTitle(),
            'subtitle' => $this->reportPdfSubtitle(),
            'bodyHtml' => $this->reportPdfBodyHtml(),
        ]);

        Mail::to($this->emailTo)->send(new DocumentMail(
            headline: $this->emailSubject !== '' ? $this->emailSubject : $this->reportPdfTitle(),
            intro: 'Please find the attached report: '.$this->reportPdfTitle().'.',
            pdf: [
                'filename' => $this->reportPdfFilename(),
                'content' => $content,
            ],
        ));

        $this->showEmailModal = false;
        $this->dispatch('be-toast', message: 'Report emailed to '.$this->emailTo);
    }
}
