<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionScore extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $course;
    public $assignmentTitle;
    public $score;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $course, $assignmentTitle, $score)
    {
        $this->student = $student;
        $this->course = $course;
        $this->assignmentTitle = $assignmentTitle;
        $this->score = $score;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Submission Score - ' . $this->course->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.submission',
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
