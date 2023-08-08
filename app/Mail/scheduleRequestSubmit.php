<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class scheduleRequestSubmit extends Mailable
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
        return $this->subject(trans('labels.scheduling.new_request').': '. $this->scheduleRequest->course->abbrv . ' ' . $this->scheduleRequest->shortDate())
                    ->view('emails.scheduleRequestSubmit');
    }
}
