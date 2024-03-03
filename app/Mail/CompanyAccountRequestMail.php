<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyAccountRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $quizUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($name,$quizUrl)
    {
        $this->name = $name;
        $this->quizUrl = $quizUrl;



    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Company Account Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.exams.company-account-request-mail',
            // with:['name' => $this->name]

            with:['name' => $this->name,'quizUrl' => $this->quizUrl]


        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
