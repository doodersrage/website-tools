<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportResults extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $reportHtml,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CM Sera Keyword Search Tool Results '.now()->format('m-d-Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->reportHtml,
        );
    }
}
