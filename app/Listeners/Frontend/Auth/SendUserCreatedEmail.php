<?php

namespace App\Listeners\Frontend\Auth;

use App\Events\Frontend\Auth\UserConfirmed;
use App\Mail\SendSiteEmail;
use App\Models\Site\SiteEmails;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Session;

class SendUserCreatedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserConfirmed $event)
    {
        $siteEmails = SiteEmails::join('email_types', 'site_emails.email_type_id', '=', 'email_types.id')
            ->select('site_emails.*')
            ->where('site_emails.site_id', '=', Session::get('site_id'))
            ->where('email_types.name', '=', 'Confirm New Account')
            ->get();
        
        foreach ($siteEmails as $email) {

            $subjectArr = [
                // convert Tiny MCE Vars here and remove tags
                '<p>' => '',
                '</p>' => '',
                '&nbsp;' => ' ',
                '{{first_name}}' => $event->user->first_name,
                '{{last_name}}' => $event->user->last_name
            ];
            $resultSubject = str_replace(array_keys($subjectArr), array_values($subjectArr), $email->subject);
            $email->subject = $resultSubject;


            $arr = [            
                '{{first_name}}' => $event->user->first_name,
                '{{last_name}}' => $event->user->last_name
            ];
    
            // Looks through string and swaps with data
            $result = str_replace(array_keys($arr), array_values($arr), $email->body);  
    
            // reset the body value with the new stuff
            $email->body = $result;
            
            // Call our Mail service
            \Mail::to($event->user->email)->send(
                new SendSiteEmail($email)
            );
        } 
    }
}
