<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class scheduleCommentNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $scheduleComment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($scheduleComment)
    {
        $this->scheduleComment = $scheduleComment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->scheduleComment->isEvent) {
            return $this->subject(trans('labels.scheduling.new_comment_on'). ' ' .$this->scheduleComment->courseInstance->course->abbrv. ' ' .trans('labels.scheduling.event_on'). ' ' .Carbon::parse($this->scheduleComment->start_time)->format('m/d/y g:i A'))
                ->view('emails.scheduleCommentNotification');
        } else {
            return $this->subject(trans('labels.scheduling.new_comment_on'). ' ' .$this->scheduleComment->course->abbrv. ' ' .trans('labels.scheduling.schedule_request_for'). ' ' .Carbon::parse($this->scheduleComment->start_time)->format('m/d/y g:i A'))
                ->view('emails.scheduleCommentNotification');
        }
    }
}
