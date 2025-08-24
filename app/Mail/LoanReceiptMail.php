<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;
    public $pdfData;
    public string $customSubject;
    public string $customBody;

    public function __construct(Transaction $transaction, $pdfData, string $customSubject, string $customBody)
    {
        $this->transaction = $transaction;
        $this->pdfData = $pdfData;
        $this->customSubject = $customSubject;
        $this->customBody = $customBody;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->customSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.custom',
            with: ['bodyContent' => $this->customBody] // <-- INI PERBAIKANNYA
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfData, 'loan-receipt-' . $this->transaction->transaction_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
