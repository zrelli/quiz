<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMemberExamCompletedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $quizUrl;
    public $quiz;

    /**
     * Create a new message instance.
     */
    public function __construct($name,$quizUrl,$quiz)
    {
        $this->name = $name;
        $this->quizUrl = $quizUrl;
        $this->quiz = $quiz;



    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->name . ' has completed an exam',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.exams.member-complete-exam',
            // with:['name' => $this->name]

            with:['name' => $this->name,'quizUrl' => $this->quizUrl,'quiz' => $this->quiz]


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
