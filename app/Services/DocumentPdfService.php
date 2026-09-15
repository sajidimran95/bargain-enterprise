<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentPdfService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function download(string $view, array $data, string $filename): Response
    {
        return Pdf::loadView($view, $data)
            ->setPaper('letter')
            ->download($filename);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function output(string $view, array $data): string
    {
        return Pdf::loadView($view, $data)
            ->setPaper('letter')
            ->output();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function streamDownload(string $view, array $data, string $filename): StreamedResponse
    {
        $pdf = $this->output($view, $data);

        return response()->streamDownload(
            function () use ($pdf): void {
                echo $pdf;
            },
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
