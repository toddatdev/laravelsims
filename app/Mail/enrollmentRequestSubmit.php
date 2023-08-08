<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class enrollmentRequestSubmit extends Mailable
{
    use Queueable, SerializesModels;
    public $enrollmentRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enrollmentRequest)
    {
        $this->enrollmentRequest = $enrollmentRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->enrollmentRequest->event->DisplayEventNameShort . ' ' . trans('labels.enrollment.new_request_email_subject'))
            ->view('emails.enrollmentRequestSubmit');
    }
}
