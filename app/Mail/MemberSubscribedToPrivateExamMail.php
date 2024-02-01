<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberSubscribedToPrivateExamMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $urlToken;
    public $quiz;

    /**
     * Create a new message instance.
     */
    public function __construct($name,$urlToken,$quiz)
    {
        $this->name = $name;
        $this->urlToken = $urlToken;
        $this->quiz = $quiz;



    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been subscribed to a private exam',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.exams.private-exam-subscription',
            with:['name' => $this->name,'urlToken' => $this->urlToken,'quiz' => $this->quiz]
            
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
