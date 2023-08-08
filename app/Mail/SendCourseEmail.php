<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCourseEmail extends Mailable
{

    use Queueable, SerializesModels;

    // Always takes in a SiteEmail, Listener is responsible for sending correct data w/ TinyMCE tags removed
    public $courseEmail;

    public function __construct($courseEmail) {
        $this->courseEmail = $courseEmail;
    }


    /**
     * @return sendCourseEmail view, can access data in view file with $courseEmail->{some attribute}
     */
    public function build() {
        return $this->subject($this->courseEmail->subject)->view('emails.sendCourseEmail');
    }

}