<?php

namespace App\Listeners\Frontend\Course;

use App\Events\Frontend\Course\UserAddedToCourse;
use App\Mail\SendSiteEmail; // its only named this becasue it was the first instance when I created sending emails
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Course\CourseEmails;
use App\Models\Access\RoleUser\RoleUser;
use App\Models\Email\SentEmails;
use App\Repositories\Backend\Access\Role\RoleRepository;
// use \Illuminate\Mail\Events\MessageSending;
use Session;

class SendUserAddedToCourseEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $roles;

    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }
    

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserAddedToCourse $event) {
        $toArr = [];
        $ccArr = [];
        $bccArr = [];
        // Check if Course Email for a Specific course has been created
        $courseEmails = CourseEmails::join('email_types', 'email_types.id', '=', 'course_emails.email_type_id')
            ->select('course_emails.*')
            ->where('course_emails.course_id', $event->course->id)
            ->where('email_types.name', '=', 'Add to Course')
            ->get();

        foreach ($courseEmails as $email) {
            // get the To's
            $tos = $this->roles->getRoleEmails($email->to_roles, $email->course_id, null);
            $toArr = $tos;

            // Get the to others
            $to_others = array_filter(explode(',', $email->to_other));
            if (isset($to_others)) {
                foreach ($to_others as $otherT) {
                    if(!in_array(trim($otherT), $toArr, true)) {
                        array_push($toArr, trim($otherT));
                    }
                }
            }
            


            // Do same for CC'c
            $ccs = $this->roles->getRoleEmails($email->cc_roles, $email->course_id, null);
            $ccArr = $ccs;

            // Get the cc others
            $cc_others = array_filter(explode(',', $email->cc_other));
            if (isset($cc_others)) {
                foreach ($cc_others as $otherC) {
                    if(!in_array(trim($otherC), $ccArr, true)) {
                        array_push($ccArr, trim($otherC));
                    }
                }
            }


            // Do same for BCC'c
            $bccs = $this->roles->getRoleEmails($email->bcc_roles, $email->course_id, null);
            $bccArr = $bccs;

            // Get the bcc others
            $bcc_others = array_filter(explode(',', $email->bcc_other));
            if (isset($bcc_others)) {
                foreach ($bcc_others as $otherB) {
                    if(!in_array(trim($otherB), $bccArr, true)) {
                        array_push($bccArr, trim($otherB));
                    }
                }
            }       

            // Convert Subject
            $subjectArr = [
                '<p>' => '',
                '</p>' => '',
                '&nbsp;' => ' ',
                '{{first_name}}' => $event->user->first_name,
                '{{last_name}}' => $event->user->last_name,
                '{{course_abbrv}}' => $event->course->abbrv,
                '{{course_name}}' => $event->course->name,
                '{{role}}' => $event->role->name,
            ];
            $subjectConversion = str_replace(array_keys($subjectArr), array_values($subjectArr), $email->subject);
            // reassign values
            $email->subject = $subjectConversion;

            // Convert Body
            $bodyArr = [
                '{{first_name}}' => $event->user->first_name,
                '{{last_name}}' => $event->user->last_name,
                '{{course_abbrv}}' => $event->course->abbrv,
                '{{course_name}}' => $event->course->name,
                '{{role}}' => $event->role->name,
            ];
            $bodyConversion = str_replace(array_keys($bodyArr), array_values($bodyArr), $email->body);
            // reassign value
            $email->body = $bodyConversion;
            
            if(!in_array($event->user->email, $toArr, true)) {
                array_push($toArr, $event->user->email);
            }

            // create record of email that is to be sent
            $sent = SentEmails::create([
                'site_email_id' => null,
                'course_email_id' => $email->id,
                'event_email_id' => null,
                'primary_recipient'=> $event->user->email,
                'to' => implode(', ', $toArr),
                'cc' => implode(', ', $ccArr),
                'bcc' => implode(', ', $bccArr),
                'subject' => $email->subject,
                'body' => $email->body,
                'mailgun_id' => '' // this gets set in app/Listeners/Emails/MessageWasSent.php
            ]);

            // Need to add this to our email for MessageWasSent Listener
            $email['sent_id'] = $sent->id;

            // Send To MailgunTransport
            \Mail::to($toArr)->cc($ccArr)->bcc($bccArr)->send(new SendSiteEmail($email));  
        }      
    }
}
