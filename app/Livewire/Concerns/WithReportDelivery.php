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

    abstract protected function reportPdfTitle(): string;

    abstract protected function reportPdfSubtitle(): ?string;

    abstract protected function reportPdfBodyHtml(): string;

    abstract protected function reportPdfFilename(): string;

    public function openEmailModal(): void
    {
        $this->showEmailModal = true;
        $this->emailTo = auth()->user()?->email ?? '';
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
        ]);

        $content = app(DocumentPdfService::class)->output('pdf.report', [
            'title' => $this->reportPdfTitle(),
            'subtitle' => $this->reportPdfSubtitle(),
            'bodyHtml' => $this->reportPdfBodyHtml(),
        ]);

        Mail::to($this->emailTo)->send(new DocumentMail(
            headline: $this->reportPdfTitle(),
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
