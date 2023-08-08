<?php

namespace App\Http\Controllers\Api\CourseInstance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Site\Site;
use Session;
use Auth;
use App\Models\CourseInstance\EventEmails;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Models\Email\SentEmails;
use App\Mail\ExceptionOccured;

class EventEmailApiController extends Controller {

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @var ExceptionOccuredEmailAddress
     */
    protected $admin_email;
    
    /**
     * Used To Access the User based on their roles, to send email to
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles) {
        $this->roles = $roles;
        $this->admin_email = env('ADMIN_EMAIL');        
    }

    /**
     * Finds and returns an Event Email Instance
     * @api /email/{id}
     * @param EventEmailID
     * @return eventEmail
     */
    public function email ($id) {
        $email = EventEmails::findOrFail($id);
        return response()->json($email, 200);
    }

    /**
     * Returns Every Event Email that is not soft deleleted
     * @api /emails
     * @return EventEmail
     */
    public function show() {
        $emails = EventEmails::get();
        return response()->json($emails, 200);
    }


    /**
     * Update Event Email was_sent to 1
     * @api /sent/email/{id}
     * @param id - Event Email ID
     * @return success
     */
    public function update($id) {
        $email = EventEmails::findOrFail($id);
        $email->was_sent = 1;
        $email->save();
        return response()->json(['message' => 'Record was updated'], 200);
    }


    /**
     * Create New Send Email Record
     * FYI - When accessing data with (->) only work on first level cannot use when access sub level data
     * @api /sent
     * @param request
     * @return success
     */
    public function sent(Request $request) {
        SentEmails::create([
            'site_email_id' => null,
            'course_email_id' => null,
            'event_email_id' => $request->email['id'],
            'primary_recipient'=> 'Auto Email',
            'to' => implode(', ', $request->email['to']),
            'cc' => implode(', ', $request->email['cc']),
            'bcc' => implode(', ', $request->email['bcc']),
            'subject' => $request->email['email']['subject'],
            'body' => $request->email['email']['body'],
            'mailgun_id' => $request['mailgun_response']
        ]);
        return response()->json(['message' => 'Record was created'], 200);
    }


    /**
     * Returns Every Email that has been sent
     * @api /sent/all
     * @return SentEmail
     */
    public function sentAll() {
        $emails = SentEmails::get();
        return response()->json($emails, 200);
    }


    /**
     * Returns Event Email that need to be sent via mailgun
     * @api /send/emails
     */
    public function send() {        
        try {
            // Main Email arr
            $jsonObj = [];

            $now = Carbon::now('UTC')->format('Y-m-d H:i:s');

            $eventEmails = EventEmails::where('event_emails.was_sent', 0)
                ->where('event_emails.send_at', '<=', $now)
                ->has('eventToTouch') // We need to make sure there are no deleted events irrespective of sites
                ->get();  
            foreach ($eventEmails as $email) {
                $toArr = [];
                $ccArr = [];
                $bccArr = [];

                $siteUrl = $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->SiteToTouch->url_root;
                $dashboardUrl = "https://".$siteUrl."/courseInstance/events/event-dashboard/";

                // get the To's  //Need to use eventToTouch, CourseInstanceToTouch, etc. because the Site Scope doesn't work with the API
                $tos = $this->roles->getRoleEmails($email->to_roles, $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->id, $email->eventToTouch->id);
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
                
                // Do same for CC'c //Need to use eventToTouch, CourseInstanceToTouch, etc. because the Site Scope doesn't work with the API
                $ccs = $this->roles->getRoleEmails($email->cc_roles, $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->id, $email->eventToTouch->id);
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


                // Do same for BCC'c //Need to use eventToTouch, CourseInstanceToTouch, etc. because the Site Scope doesn't work with the API
                $bccs = $this->roles->getRoleEmails($email->bcc_roles, $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->id, $email->eventToTouch->id);
                $bccArr = $bccs;

                // // Get the bcc others
                $bcc_others = array_filter(explode(',', $email->bcc_other));
                if (isset($bcc_others)) {
                    foreach ($bcc_others as $otherB) {
                        if(!in_array(trim($otherB), $bccArr, true)) {
                            array_push($bccArr, trim($otherB));
                        }
                    }
                } 

                // remove <p> tags from items that use their own on tinyMCE
                $rPTag = [
                    '<p>' => '',
                    '</p>' => '',
                ];
            
                // Convert Subject
                $subjectArr = [
                    '<p>' => '',
                    '</p>' => '',
                    '&nbsp;' => ' ',
                    '{{first_name}}' => '',  // // These are not $event->user->first_name because these are not about a specific person -jl 2020-02-17 14:23
                    '{{last_name}}' => '',   //
                    '{{course_name}}'  => $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->name,
                    '{{course_abbrv}}' => $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->abbrv,
                    '{{event_dashboard_link}}' => '<a href="'.$dashboardUrl.$email->eventToTouch->id.'">'.trans("buttons.event.dashboard").'</a>',
                    '{{event_date_short}}' => Carbon::parse($email->eventToTouch->start_time)->format('n/j/y'),
                    '{{event_date_long}}'  => Carbon::parse($email->eventToTouch->start_time)->format('F jS, Y'),
                    '{{event_day}}'        => Carbon::parse($email->eventToTouch->start_time)->format('l'),
                    '{{event_start_time}}' => Carbon::parse($email->eventToTouch->start_time)->format('g:i A'),
                    '{{event_end_time}}'   => Carbon::parse($email->eventToTouch->end_time)->format('g:i A'),
                    '{{init_mtg_room_full}}'   => $email->eventToTouch->InitialMeetingRoom->description,
                    '{{init_mtg_room_abbrv}}'  => $email->eventToTouch->InitialMeetingRoom->abbrv,
                    '{{event_notes}}'          => $email->eventToTouch->public_comments,
                    '{{event_internal_notes}}' => $email->eventToTouch->internal_comments,
                    '{{faculty_start_time}}'   => Carbon::parse($email->eventToTouch->start_time)->addMinutes($email->eventToTouch->fac_report)->format('g:i A'),
                    '{{faculty_leave_time}}'   => Carbon::parse($email->eventToTouch->end_time)->addMinutes($email->eventToTouch->fac_leave)->format('g:i A'),
                    '{{location_name_full}}'  => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->name,
                    '{{location_name_abbrv}}' => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->abbrv,
                    '{{location_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->eventToTouch->InitialMeetingRoom->LocationToTouch->more_info),
                    '{{building_name_full}}'  => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->name,
                    '{{building_name_abbrv}}' => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->abbrv,
                    '{{building_map_url}}'    => '<a href="'.$email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->map_url.'">'.trans("labels.buildings.map").'</a>',    
                    '{{building_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->more_info),
                    '{{role}}' => '',
                ];
                $subjectConversion = str_replace(array_keys($subjectArr), array_values($subjectArr), $email->subject);
                // reassign values
                $email->subject = $subjectConversion;
                
                // Convert Body
                $bodyArr = [
                    '{{first_name}}' => '', // These are not $event->user->first_name because these are not about a specific person -jl 2020-02-17 14:23
                    '{{last_name}}' => '',  //
                    '{{course_name}}'  => $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->name,
                    '{{course_abbrv}}' => $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->abbrv,
                    '{{event_dashboard_link}}' => '<a href="'.$dashboardUrl.$email->eventToTouch->id.'">'.trans("buttons.event.dashboard").'</a>',
                    '{{event_date_short}}' => Carbon::parse($email->eventToTouch->start_time)->format('n/j/y'),
                    '{{event_date_long}}'  => Carbon::parse($email->eventToTouch->start_time)->format('F jS, Y'),
                    '{{event_day}}'        => Carbon::parse($email->eventToTouch->start_time)->format('l'),
                    '{{event_start_time}}' => Carbon::parse($email->eventToTouch->start_time)->format('g:i A'),
                    '{{event_end_time}}'   => Carbon::parse($email->eventToTouch->end_time)->format('g:i A'),
                    '{{init_mtg_room_full}}'   => $email->eventToTouch->InitialMeetingRoom->description,
                    '{{init_mtg_room_abbrv}}'  => $email->eventToTouch->InitialMeetingRoom->abbrv,
                    '{{event_notes}}'          => $email->eventToTouch->public_comments,
                    '{{event_internal_notes}}' => $email->eventToTouch->internal_comments,
                    '{{faculty_start_time}}'   => Carbon::parse($email->eventToTouch->start_time)->addMinutes($email->eventToTouch->fac_report)->format('g:i A'),
                    '{{faculty_leave_time}}'   => Carbon::parse($email->eventToTouch->end_time)->addMinutes($email->eventToTouch->fac_leave)->format('g:i A'),
                    '{{location_name_full}}'  => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->name,
                    '{{location_name_abbrv}}' => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->abbrv,
                    '{{location_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->eventToTouch->InitialMeetingRoom->LocationToTouch->more_info),
                    '{{building_name_full}}'  => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->name,
                    '{{building_name_abbrv}}' => $email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->abbrv,
                    '{{building_map_url}}'    => '<a href="'.$email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->map_url.'">'.trans("labels.buildings.map").'</a>',    
                    '{{building_more_info}}'  => str_replace(array_keys($rPTag), array_values($rPTag), $email->eventToTouch->InitialMeetingRoom->LocationToTouch->BuildingToTouch->more_info),
                    '{{role}}' => '',     
                ];
                $bodyConversion = str_replace(array_keys($bodyArr), array_values($bodyArr), $email->body);
                // reassign value
                $email->body = $bodyConversion;
                
                // Only add to API if it has an address to send to
                if (!empty($toArr) || !empty($ccArr) || !empty($bccArr)) {
                    $jsonObj[] = [
                        'id' => $email->id,
                        'site_id' => $email->eventToTouch->CourseInstanceToTouch->CourseToTouch->SiteToTouch->id,
                        'send_at' => $email->send_at,
                        'email' => [
                            'subject' => $email->subject,
                            'body' => $email->body,
                        ],
                        'to' => $toArr,
                        'cc' => $ccArr,
                        'bcc' => $bccArr,
                        'reply_to' => $email->reply_to
                    ];
                }
            } 
            return response()->json($jsonObj, 200);
        }catch (\Throwable $exc) {
           $errorMsg  = "Message: ". $exc->getMessage(). "<br />";
           $errorMsg .= "\nFile and Line: ". $exc->getFile() .":". $exc->getLine(). "<br />";
           $errorMsg .= "Code:\n". $exc->getCode(). "<br />"; 
            \Mail::to($this->admin_email)->send(new ExceptionOccured($errorMsg));
        }
    }
}