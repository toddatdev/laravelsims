<?php

namespace App\Http\Controllers\CourseInstance;

use Auth;
use Session;
use App\Models\Access\User\User;
use App\Repositories\Backend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\ScheduleRequest;
use App\Models\CourseInstance\ScheduleComment;
use App\Models\Location\LocationSchedulers;
use Carbon\Carbon;
use App\Models\Site\Site;
use Mail;
use App\Mail\scheduleCommentNotification;
use App\Http\Requests\CourseInstance\StoreCommentRequest;


class ScheduleCommentController extends Controller
{

    /**
     * Store a newly created schedule request comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {

        $user = Auth::user();
        $timestamp = Carbon::now();
        $site_id = Session::get('site_id');

        $tab = $request->tab;

        // comment post variables
        $comment = $request->post('comment');
        $scheduleRequestId = $request->post('schedule_request_id');
        $eventId = $request->post('event_id');
        $emailComment = $request->post('email_comment');
        $resolved = $request->post('resolved');
        $url_root = "http://" . Site::find($site_id)->url_root;
        // If this was a requested event, find the original requestor. 
        $requestor = null;
        if($scheduleRequestId) {
            $requestor = User::findOrFail(ScheduleRequest::find($scheduleRequestId)->requested_by);
        }
        // if event id exists - pull the event information - if not pull the schedule requests
        if($eventId) {
            $scheduleCommentInfo = Event::find($eventId);
            $scheduleCommentInfo->isEvent = true;
            //this is based on an event, drill down to get the location ID -jl 2020-04-17 14:01
            $scheduleCommentInfo->location_id = $scheduleCommentInfo->initialMeetingRoom->location->id; 
        } else {
            $scheduleCommentInfo = ScheduleRequest::find($scheduleRequestId);
            $scheduleCommentInfo->isEvent = false;
            //this is based on a request, just get the location ID -jl 2020-04-17 14:01
            $scheduleCommentInfo->location_id = $scheduleCommentInfo->location_id;
        }

        // users with scheduling permission for this location
        $locationSchedulersList = User::whereHas('schedulingLocations', function($q) use ($scheduleCommentInfo) {
                $q->where('location_id', $scheduleCommentInfo->location_id);
                })
            ->where('id', '!=', $user->id) //Don't need the comment in the schedulers email
            ->get();

        if ($emailComment) { // The "Send comment notification email" checkbox was selected on the comment.
            // add variables for the scheduleCommentNotifcation blade
            $scheduleCommentInfo->email_comment = $comment;
            $scheduleCommentInfo->email_author  = $user->first_name .' '. $user->last_name;
            $scheduleCommentInfo->email_timestamp = Carbon::now()->format('m/d/y g:i A');
            $scheduleCommentInfo->email_url = $url_root . '/courseInstance/events/event-dashboard/' .$eventId. '/comments';
            $scheduleCommentInfo->start_str = Carbon::parse($scheduleCommentInfo->start_time)->format('m/d/y g:i A');

            //if this is an event we send everyone the event link on the calendar
            if($eventId) {
                //If this is an event that was requested, and the requestor is not the user submitting the comment send them an email
                if (   $requestor
                    && $requestor->id != $user->id) {
                    Mail::to($requestor)->send(new scheduleCommentNotification($scheduleCommentInfo));
                    }
            } else { //This is a schedule request without an event yet..
                //This schedule requestor is not the person submitting the comment, send them an email
                if ($requestor->id != $user->id) 
                {
                    //It's only a request at this point, and this is the requestor, send them to their Pending Request page.
                    $scheduleCommentInfo->email_url = $url_root . '/myScheduleRequest/pending?id='. $scheduleCommentInfo->id;
                    Mail::to($requestor)->send(new scheduleCommentNotification($scheduleCommentInfo));
                }
                //It's only a request at this point, set the URL to the pending requests page for the Schedulers email below.
                $scheduleCommentInfo->email_url = $url_root . '/scheduleRequest/pending?id='. $scheduleCommentInfo->id;
            }
            // send to all users with the scheduling permission for this location, if there are any
            if (count($locationSchedulersList) > 0 ) {
                Mail::to($locationSchedulersList)->send(new scheduleCommentNotification($scheduleCommentInfo));
            }
        } //if ($emailComment)

        // write to db - if event id exists write by the event_id if not store by schedule request id
        if($eventId) {

            // if user is a scheduler, write resolved value, if not - set to 0
            if ($user->hasPermission('scheduling')) {
                if ($resolved) {
                    $resolved = 1;
                } else {
                    $resolved = 0;
                }
                $event = Event::find($eventId);
                $event->resolved = $resolved;
                $event->save();
            } else {
                $event = Event::find($eventId);
                $event->resolved = 0;
                $event->save();
            }

        
            $scheduleComment = ScheduleComment::create([
                'event_id' => $eventId,
                'comment' => $comment,
                'created_by' => $user->id,
                'last_edited_by' => $user->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
    
        } else {
            $scheduleComment = ScheduleComment::create([
                'schedule_request_id' => $scheduleRequestId,
                'comment' => $comment,
                'created_by' => $user->id,
                'last_edited_by' => $user->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
        }


        // return info for the comment to display on page - needs error checking
        $response = array(
            'status' => 'success',
            'name' => $user->first_name .' '. $user->last_name,
            'date_time' => Carbon::parse(now())->timezone(session('timezone'))->format('m/d/y g:i A'),
            'comment' => $comment
        );

        //figure out what page they came from and take them back there
        if(Str::contains($request->headers->get('referer'), ['event-dashboard']))
        {
            //todo: mitcks 12-17 if I do a flash success here it does not return to comment tab? The with isn't really doing anything currently
            return redirect()->route('event_dashboard',[$eventId, $tab])
                ->with('success','Comment added successfully.');
        }
        else
        {
            //this is for schedule requests and the old event info modal
            return $response;
        }

    }

    
    /**
     * Soft delete from frontend - updates deleted_at.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $scheduleComment = ScheduleComment::find($id);
        $scheduleComment->deleted_at = Carbon::now();
        $scheduleComment->save();

        return redirect()->route('pending_requests');
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
