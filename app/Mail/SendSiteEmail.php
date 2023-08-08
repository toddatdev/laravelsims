<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSiteEmail extends Mailable 
{
    use Queueable, SerializesModels;

    // Always takes in a SiteEmail, Listener is responsible for sending correct data w/ TinyMCE tags removed
    public $siteEmail;

    public function __construct($siteEmail) {
        $this->siteEmail = $siteEmail;
    }


    /**
     * @return sendSiteEmail view, can access data in view file with $siteEmail->{some attribute} 
     */
    public function build() {
        return $this->subject($this->siteEmail->subject)->view('emails.sendSiteEmail');
    }

}