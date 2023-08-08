<?php

namespace App\Listeners\Backend\Site;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Backend\Site\SendManualEmail;
use App\Mail\SendSiteEmail; // This Class just creates a view to add our email to
use App\Models\Access\RoleUser\RoleUser;
use App\Models\Email\SentEmails;
use Session;

class SendEmailManually
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SendManualEmail $event) {

        $toArr = [];
        $ccArr = [];
        $bccArr = [];

        $tos = explode(',', $event->email->to_roles);
        //dd($tos);

        foreach ($tos as $to) {
            $roleT = RoleUser::where('role_id', $to)
                ->distinct()
                ->join('users', 'users.id', '=', 'role_user.user_id')
                ->pluck('users.email');
            foreach ($roleT->all() as $key => $t) {
                array_push($toArr, $t);
            }
        }

        //dd($roleT);

        // Get the to others
        $to_others = array_filter(explode(',', $event->email->to_other));
        if (isset($to_others)) {
            foreach ($to_others as $otherT) {
                // trim removes white space from explode. strict is set to true, but we are getting and expecting a string to it doesn't mean much
                if(!in_array(trim($otherT), $toArr, true)) {
                    array_push($toArr, trim($otherT));
                }
            }
        }


        // Do same for CC'c
        $ccs = explode(',', $event->email->cc_roles);
        foreach ($ccs as $cc) {
            $roleC = RoleUser::where('role_id', $cc)
                ->distinct()
                ->join('users', 'users.id', '=', 'role_user.user_id')
                ->pluck('users.email'); 
            foreach ($roleC->all() as $key => $c) {
                array_push($ccArr, $c);
            }
        }

        // Get the cc others
        $cc_others = array_filter(explode(',', $event->email->cc_other));
        if (isset($cc_others)) {
            foreach ($cc_others as $otherC) {
                if(!in_array(trim($otherC), $ccArr, true)) {
                    array_push($ccArr, trim($otherC));
                }
            }
        }


        // Do same for BCC'c
        $bccs = explode(',', $event->email->bcc_roles);
        foreach ($bccs as $bcc) {
            $roleB = RoleUser::where('role_id', $bcc)
                ->distinct()
                ->join('users', 'users.id', '=', 'role_user.user_id')
                ->pluck('users.email'); 
            foreach ($roleB->all() as $key => $b) {
                array_push($bccArr, $b);
            }
        }

        // Get the bcc others
        $bcc_others = array_filter(explode(',', $event->email->bcc_other));
        if (isset($bcc_others)) {
            foreach ($bcc_others as $otherB) {
                if(!in_array(trim($otherB), $bccArr, true)) {
                    array_push($bccArr, trim($otherB));
                }
            }
        }

        $subjectArr = [
            '<p>' => '',
            '</p>' => '',
            '&nbsp;' => ' ',
        ];
        $subjectConversion = str_replace(array_keys($subjectArr), array_values($subjectArr), $event->email->subject);
        // reassign values
        $event->email->subject = $subjectConversion;
    
        $sent = SentEmails::create([
            'site_email_id' => $event->email->site_email_id,
            'course_email_id' => null,
            'event_email_id' => null,
            'primary_recipient'=> 'site notification',
            'to' => implode(', ', $toArr),
            'cc' => implode(', ', $ccArr),
            'bcc' => implode(', ', $bccArr),
            'subject' => $event->email->subject,
            'body' => $event->email->body,
            'mailgun_id' => '' // this gets set in app/Listeners/Emails/MessageWasSent.php
        ]);

        // Need to add this to our email for MessageWasSent Listener
        $event->email->sent_id = $sent->id;

        // Send To MailgunTransport
        \Mail::to($toArr)->cc($ccArr)->bcc($bccArr)->send(new SendSiteEmail($event->email));           

    }
}
