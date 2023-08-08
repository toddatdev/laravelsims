<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class scheduleRequestDeny extends Mailable
{
    use Queueable, SerializesModels;
    public $scheduleRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($scheduleRequest)
    {
        $this->scheduleRequest = $scheduleRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Denied Request: '. $this->scheduleRequest->course->name) 
                    ->view('emails.scheduleRequestDeny');
    }
}
