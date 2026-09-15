<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{filename: string, content: string}|null  $pdf
     */
    public function __construct(
        public string $headline,
        public string $intro,
        public ?array $pdf = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->headline,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.document',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if ($this->pdf === null) {
            return [];
        }

        return [
            Attachment::fromData(fn () => $this->pdf['content'], $this->pdf['filename'])
                ->withMime('application/pdf'),
        ];
    }
}
