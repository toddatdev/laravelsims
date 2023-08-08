<?php

namespace App\Listeners\Frontend\Event;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Frontend\Event\UserAddedToEvent;
use App\Models\Email\SentEmails;
use App\Mail\SendSiteEmail; // This Class just creates a view to add our email to
use App\Models\CourseInstance\EventEmails;
use App\Models\Access\RoleUser\RoleUser;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Models\Site\Site;
use Session;
use Carbon\Carbon;
use App\Models\CourseInstance\EventUser;

class SendUserAddedToEventEmail
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
    public function handle(UserAddedToEvent $userAddEvent) {

        // ? $userAddEvent->event->event->eventUsers will return all the event users, we only want the one who the emails is going to
        // I think we can still use this, but will need to loop the array and do a comparison to find our data
        // So creating a new Db query to do that
        // Get the event_role associated with a user and event
        $eventRoleAssignedToUser = EventUser::where('user_id', $userAddEvent->user->id)
            ->where('event_id', $userAddEvent->event->id)
            ->where('role_id', $userAddEvent->role->id)
            ->latest()->first(); // this is essential getting the last ID (based on our query), but Laravel doesn't have that method ... still no clue why

        // Get the site URL for the Dashboard, etc. 
        $siteUrl = Site::find(SESSION::get('site_id'))->url_root;
        $dashboardUrl = "https://".$siteUrl."/courseInstance/events/event-dashboard/";           

        $eventEmails = EventEmails::where('event_emails.event_id', $userAddEvent->event->id)
                                ->where('event_emails.email_type_id', 4) //Add to event
                                ->get();    

        foreach ($eventEmails as $email) {
            //these should be reset each time we get an email.
            $ccArr = [];
            $bccArr = [];

            // If the user is not assigned the event_role associated with this email, stop this loop and go to next iteration
            if (!in_array($eventRoleAssignedToUser->role_id, $email->toRoles())) {
                // Stopping this loop, going to next index
                continue;
            }
            // else go one w/ rest of code

            // Send the To field to just the user that has been added.
            $toArr[] = $userAddEvent->user->email;
            // There are no other to's in an "Add to event". The to field just has the user that was added.

            // Get any CC's
            $ccs = $this->roles->getRoleEmails($email->cc_roles, $email->course_id, $userAddEvent->event->id);
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
            $bccs = $this->roles->getRoleEmails($email->bcc_roles, $email->course_id, $userAddEvent->event->id);
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

            // remove <p> tags from items that use thier own on tinyMCE
            $rPTag = [
                '<p>' => '',
                '</p>' => '',
            ];
        
            // Convert Subject
            $subjectArr = [
                '<p>' => '',
                '</p>' => '',
                '&nbsp;' => ' ',
                '{{first_name}}'   => $userAddEvent->user->first_name,
                '{{last_name}}'    => $userAddEvent->user->last_name,
                '{{course_name}}'  => $email->event->courseInstance->course->name,
                '{{course_abbrv}}' => $email->event->courseInstance->course->abbrv,
                '{{event_dashboard_link}}' => '<a href="'.$dashboardUrl.$email->event->id.'">'.trans("buttons.event.dashboard").'</a>',
                '{{event_date_short}}' => Carbon::parse($email->event->start_time)->format('n/j/y'),
                '{{event_date_long}}'  => Carbon::parse($email->event->start_time)->format('F jS, Y'),
                '{{event_day}}'        => Carbon::parse($email->event->start_time)->format('l'),
                '{{event_start_time}}' => Carbon::parse($email->event->start_time)->format('g:i A'),
                '{{event_end_time}}'   => Carbon::parse($email->event->end_time)->format('g:i A'),
                '{{init_mtg_room_full}}'   => $email->event->InitialMeetingRoom->description,
                '{{init_mtg_room_abbrv}}'  => $email->event->InitialMeetingRoom->abbrv,
                '{{event_notes}}'          => $email->event->public_comments,
                '{{event_internal_notes}}' => $email->event->internal_comments,
                '{{faculty_start_time}}'   => Carbon::parse($email->event->start_time)->addMinutes($email->event->fac_report)->format('g:i A'),
                '{{faculty_leave_time}}'   => Carbon::parse($email->event->end_time)->addMinutes($email->event->fac_leave)->format('g:i A'),
                '{{location_name_full}}'  => $email->event->InitialMeetingRoom->Location->name,
                '{{location_name_abbrv}}' => $email->event->InitialMeetingRoom->Location->abbrv,
                '{{location_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->event->InitialMeetingRoom->Location->more_info),
                '{{building_name_full}}'  => $email->event->InitialMeetingRoom->Location->Building->name,
                '{{building_name_abbrv}}' => $email->event->InitialMeetingRoom->Location->Building->abbrv,
                '{{building_map_url}}'    => '<a href="'.$email->event->InitialMeetingRoom->Location->Building->map_url.'">'.trans("labels.buildings.map").'</a>',    
                '{{building_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->event->InitialMeetingRoom->Location->Building->more_info),
                '{{role}}' => $userAddEvent->role->name,     
            ];
            $subjectConversion = str_replace(array_keys($subjectArr), array_values($subjectArr), $email->subject);
            // reassign values
            $email->subject = $subjectConversion;

            // Convert Body
            $bodyArr = [
                '{{first_name}}'   => $userAddEvent->user->first_name,
                '{{last_name}}'    => $userAddEvent->user->last_name,
                '{{course_name}}'  => $email->event->courseInstance->course->name,
                '{{course_abbrv}}' => $email->event->courseInstance->course->abbrv,
                '{{event_dashboard_link}}' => '<a href="'.$dashboardUrl.$email->event->id.'">'.trans("buttons.event.dashboard").'</a>',
                '{{event_date_short}}' => Carbon::parse($email->event->start_time)->format('n/j/y'),
                '{{event_date_long}}'  => Carbon::parse($email->event->start_time)->format('F jS, Y'),
                '{{event_day}}'        => Carbon::parse($email->event->start_time)->format('l'),
                '{{event_start_time}}' => Carbon::parse($email->event->start_time)->format('g:i A'),
                '{{event_end_time}}'   => Carbon::parse($email->event->end_time)->format('g:i A'),
                '{{init_mtg_room_full}}'   => $email->event->InitialMeetingRoom->description,
                '{{init_mtg_room_abbrv}}'  => $email->event->InitialMeetingRoom->abbrv,
                '{{event_notes}}'          => $email->event->public_comments,
                '{{event_internal_notes}}' => $email->event->internal_comments,
                '{{faculty_start_time}}'   => Carbon::parse($email->event->start_time)->addMinutes($email->event->fac_report)->format('g:i A'),
                '{{faculty_leave_time}}'   => Carbon::parse($email->event->end_time)->addMinutes($email->event->fac_leave)->format('g:i A'),
                '{{location_name_full}}'  => $email->event->InitialMeetingRoom->Location->name,
                '{{location_name_abbrv}}' => $email->event->InitialMeetingRoom->Location->abbrv,
                '{{location_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->event->InitialMeetingRoom->Location->more_info),
                '{{building_name_full}}'  => $email->event->InitialMeetingRoom->Location->Building->name,
                '{{building_name_abbrv}}' => $email->event->InitialMeetingRoom->Location->Building->abbrv,
                '{{building_map_url}}'    => '<a href="'.$email->event->InitialMeetingRoom->Location->Building->map_url.'">'.trans("labels.buildings.map").'</a>',    
                '{{building_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->event->InitialMeetingRoom->Location->Building->more_info),
                '{{role}}' => $userAddEvent->role->name,     
            ];
            $bodyConversion = str_replace(array_keys($bodyArr), array_values($bodyArr), $email->body);
            // reassign value
            $email->body = $bodyConversion;

            // create record of email that is to be sent
            $sent = SentEmails::create([
                'site_email_id' => null,
                'course_email_id' => null,
                'event_email_id' => $email->id,
                'primary_recipient'=> $userAddEvent->user->email,
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