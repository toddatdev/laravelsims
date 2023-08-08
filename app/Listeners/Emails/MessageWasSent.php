<?php

namespace App\Listeners\Emails;

use \Illuminate\Mail\Events\MessageSent;
use App\Models\Email\SentEmails;
use Session;

class MessageWasSent
{
    /**
     * @return void
     */

    public function __construct()
    {

    }
    

    /**
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event) {
        // TC - For now only add the Mailgun ID to emails that are trigger by Event Listeners, those get stored in `sent_email_messages`
        // Which is required for this function to work properly
        if(isset($event->data['siteEmail'])) {
            $sent = SentEmails::find($event->data['siteEmail']->sent_id);
            $sent->mailgun_id = $event->message->getId();
            $sent->update();
        }
    }
}
