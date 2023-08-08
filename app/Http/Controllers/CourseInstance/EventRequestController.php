<?php

namespace App\Http\Controllers\CourseInstance;

use App\Events\Frontend\Event\UserAddedToEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseInstance\StoreEventUserRequest;
use App\Models\Access\User\User;
use App\Models\Course\Course;
use App\Models\Course\CourseCoupons;
use App\Models\Course\CourseFees;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventUser;
use App\Models\CourseInstance\EventUserHistory;
use App\Models\CourseInstance\EventUserPayment;
use App\Models\Access\Role\Role;
use App\Models\CourseInstance\EventRequest;
use App\Models\Payments\AuthNetTransactions;
use App\Models\Payments\AuthnetWebhook;
use App\Models\Site\Site;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use Mail;
use App\Mail\enrollmentRequestConfirmation;
use App\Mail\enrollmentRequestSubmit;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

//define("AUTHORIZENET_LOG_FILE","phplog");

class EventRequestController extends Controller {

    protected $roles;

    public function __construct(RoleRepository $roles, Request $request)
    {
        $this->roles = $roles;
    }

    public function index() {

    }

    public function requestEnrollment($course_id, $event_id=0) {

        $course = Course::findOrFail($course_id);

        $events = Event::with('CourseInstance')
            ->where('start_time', '>=', date("Y-m-d"))
            ->whereHas('CourseInstance', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->orderBy('start_time')
            ->get();

        // build a readable name for each event
        $event_details = [];
        if (!$events->isEmpty()) {
            foreach($events as $event)
            {
                $status = "";

                if($event->isFull())
                {
                    $status = strtoupper(trans('labels.event.full')) . " ";
                }

                //Only add to array if the event is not full OR it is full but requests are allowed
                if(!$event->isFull() OR ($event->isFull() AND $event->courseInstance->course->isOptionChecked(13)))
                {
                    $event_details[$event->id] = $status
                        . $event->getDisplayDateStartEndTimesAttribute()
                        . " - " . $event->initialMeetingRoom->location->building->abbrv
                        . ' '. $event->initialMeetingRoom->location->abbrv;
                }
            }
        }

        //for role select list
        $roles = $this->roles->getRoles(3);

        //for course fees select list (eager loading related table)
        $courseFees = CourseFees::with(['courseFeeType'])
                                ->where('course_id', $course->id)
                                ->where('retire_date', null)
                                ->get();
        $array_course_fees = array();
        foreach($courseFees as $courseFee)
        {
            $array_course_fees [$courseFee->id]= $courseFee->courseFeeType->description;
        }

        //check to see if active coupons exist
        $courseCoupons = CourseCoupons::where('course_id', $course->id)
                        ->where(function($q) {
                            $q->where('expiration_date', '>=', date("Y-m-d"))
                                ->orWhereNull('expiration_date');
                        })
                        ->get();

        return view('courseInstance.events.request.event-request-index', compact('course', 'event_details', 'roles', 'event_id', 'array_course_fees', 'courseCoupons'));
    }

    public function requestPayment($event_user_payment_id)
    {
        $eventUserPayment = EventUserPayment::find($event_user_payment_id);

        $course = $eventUserPayment->eventUser->event->CourseInstance->course;

        //get possible payment policy text from site_options
        $paymentPolicy = Site::find(Session::get('site_id'))->getSiteOption(11);

        return view('courseInstance.events.request.event-request-payment', compact('eventUserPayment', 'course', 'paymentPolicy'));
    }

    public function testReturn()
    {
        return view('courseInstance.events.request.test-return');
    }

    public function completePayment($event_user_payment_id)
    {
        $eventUserPayment = EventUserPayment::findOrFail($event_user_payment_id);
        $course = $eventUserPayment->eventUser->event->CourseInstance->course;

        return view('courseInstance.events.request.event-request-payment-confirm', compact('eventUserPayment', 'course'));

    }

    public function paymentReceipt($event_user_payment_id)
    {
        $eventUserPayment = EventUserPayment::findOrFail($event_user_payment_id);
        $authNetTransactions = AuthNetTransactions::where('event_user_payment_id', $event_user_payment_id)->first();

        // Access notification values
        $payload_json_obj = json_decode($authNetTransactions->payload);
        $transactionId = $payload_json_obj->payload->id;

        $site = Site::find(Session::get('site_id'));

        $authorize_payment_api_login_id = $site->getSiteOption(9);
        $authorize_payment_transaction_key = $site->getSiteOption(10);

        /* Create a merchantAuthenticationType object with authentication details
              retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($authorize_payment_api_login_id);
        $merchantAuthentication->setTransactionKey($authorize_payment_transaction_key);

        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transactionId);
        //$request->setTransId('40064953234'); //local test CC value
        //$request->setTransId('40065441181'); //local test Bank value

        $controller = new AnetController\GetTransactionDetailsController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        return view('courseInstance.events.request.payment-receipt', compact('eventUserPayment', 'response', 'site'));

    }

    //mitcks 2020-02-21: I know that it would probably make more sense for this store function to be in the EventUsersController
    // but, it was originally here when the event_user_requests table was being used and since the logic is different
    // between an admin adding a user and a user requesting, it seemed easier to continue to differentiate them this way.
    public function store(StoreEventUserRequest $request) {

        // Add the logged in user as the requester
        $request['user_id'] = Auth::user()->id;

        $user_id = Auth::user()->id;
        $role_id = $request['role_id'];
        $event_id = $request['event_id'];
        $request_notes = $request['comments'];
        $course_id = $request['course_id'];
        $fee_type = $request['course_fee_id'];
        $amount = $request['total_fee_value'];
        $coupon_code = $request['coupon_code'];

        // Validation request should catch this, but just in case
        if(!$event_id or !$user_id or !$role_id)
        {
            return redirect()->back()->withErrors(trans('exceptions.general.unexpected_error', ['details'=>'Required data missing for insert']));
        }

        $event = Event::find($event_id);
        $role = Role::find($role_id);

        //check to see if this is a learner role because only learners can auto register
        if($role->learner == 1)
        {
            $isLearnerRole = true;
        }
        else
        {
            $isLearnerRole = false;
        }

        // Conditional validation for course that requires payment
        $courseFees = CourseFees::where('course_id', $course_id)->get();
        $paymentRequired = false;
        //if there is at least one fee and it's a learner role then payment required = true
        if($courseFees->count() > 0 and $isLearnerRole)
        {
            $paymentRequired = true;
        }

        if($paymentRequired)
        {
            //check to make sure a fee type was selected and there is an amount
            if(!$fee_type)
            {
                return redirect()->back()->withErrors(trans('exceptions.frontend.enrollment.no_fee_type'));
            }
            else
            {
                //get fee_type description and amount to use below
                $courseFee = CourseFees::find($fee_type);
            }

            if(!$amount)
            {
                return redirect()->back()->withErrors(trans('exceptions.frontend.enrollment.no_amount'));
            }
        }

        //check to see if event is full AND auto register AND isLearner
        //redirect back with error and do not allow them to auto register if this is true
        if($event->courseInstance->course->isOptionChecked(1) AND $event->isFull() AND $isLearnerRole)
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.eventuserrequest.auto_full', ['date'=>$event->DisplayDateStartEndTimes]));
        }

        //if payment required
        if($paymentRequired)
        {
            $status_id = 5; //Pending Payment
        }
        else
        {
            //if auto register course and learner role
            if($event->courseInstance->course->isOptionChecked(1) AND $isLearnerRole)
            {
                $status_id = 1; //Enrolled
            }
            else
            {
                $status_id = 3; //Waitlist
            }
        }

        //IF THEY ARE ALREADY ACTIVELY ENROLLED IN THIS EVENT OR WAIT LISTED- DISPLAY ALERT AND STOP PROCESSING
        $eventUser = EventUser::where('event_id', $event_id)
            ->where('user_id', $user_id)
            ->first();

        if($eventUser)
        {
            //if status_id = 1 then they are already enrolled
            if($eventUser->status_id == 1)
            {
                return redirect()->back()
                    ->withFlashDanger(trans('alerts.frontend.eventuserrequest.enrolled',
                            ['date'=>$eventUser->event->DisplayDateStartEndTimes,
                                'role'=>$eventUser->role->name,
                                'eventId'=>$eventUser->event_id]));
            }
            //elseif status_id = 3 then they are on waitlist
            elseif($eventUser->status_id == 3)
            {
                return redirect()->back()
                    ->withFlashDanger(trans('alerts.frontend.eventuserrequest.waitlist',
                        ['date'=>$eventUser->event->DisplayDateStartEndTimes,
                            'role'=>$eventUser->role->name]));
            }
            elseif($eventUser->status_id == 5)
            {
                //they started the payment process before and already have pending record
                //TODO: figure out what to do here
            }
            else //this should not happen, but catch just in case
            {
                return redirect()->back()
                    ->withFlashDanger(trans('alerts.frontend.eventuserrequest.unexpected'));
            }
        }
        
        //IF THEY ARE NOT ALREADY ENROLLED/WAIT LISTED
        // FIRST check to see if there was a soft deleted record for this user/event and restore it for updating
        EventUser::withTrashed()
            ->where('event_id', $event_id)
            ->where('user_id', $user_id)
            ->restore();

        //now do updateOrCreate to either create new record or update existing
        $eventUser = EventUser::updateOrCreate(
            [
                //unique field combination
                'user_id' => $user_id,
                'event_id' => $event_id,
            ],
            [
                'role_id' => $role_id,
                'last_edited_by' => $user_id,
                'status_id' => $status_id,
                'request_notes' => $request_notes
            ]
        );

        //only set the created_by field if this is a create (otherwise it is erroneously changed on update)
        if($eventUser->wasRecentlyCreated)
        {
            $eventUser->created_by = $user_id;
            $eventUser->save();
        }

        //if payment required then create/update event_user_payments record
        if($paymentRequired)
        {
            $eventUserPayment = EventUserPayment::updateOrCreate(
                [
                    'event_user_id' => $eventUser->id,
                ],
                [
                    'fee_type_descrp' => $courseFee->courseFeeType->description,
                    'amount_before_coupon' => $courseFee->amount,
                    'coupon_code' => $coupon_code,
                    'amount_after_coupon' => $amount,
                    'created_by' => $user_id,
                    'last_edited_by' => $user_id
                ]
            );
        }

        if($paymentRequired)//redirect to payment page
        {
            return redirect()->route('paymentRequest', [$eventUserPayment->id]);
        }
        else
        {
            if ($event->courseInstance->course->isOptionChecked(1) and $isLearnerRole) //auto enroll
            {
                //update event_user_history
                $eventUserHistory = EventUserHistory::create(
                    [
                        'event_user_id' => $eventUser->id,
                        'action_id' => 2, //request access
                        'display_text' => Role::find($role_id)->name,
                        'action_by' => $user_id
                    ]
                );

                //send "add to event" email to person who added himself/herself AND NOT pending payment
                if ($eventUser) {
                    event(new UserAddedToEvent(User::find($user_id), $event, Role::find($role_id)));
                }

                // Redirect to event dashboard on success with message they have been added
                return redirect()->route('event_dashboard', [$event_id])
                    ->with('success', trans('alerts.frontend.eventusers.created',
                        ['UserName' => User::find($user_id)->name,
                            'RoleName' => Role::find($role_id)->name,
                            'EventName' => $eventUser->event->DisplayEventName]));

            }
            else //request enrollment
            {
                //update event_user_history
                $eventUserHistory = EventUserHistory::create(
                    [
                        'event_user_id' => $eventUser->id,
                        'action_id' => 5, //request access
                        'display_text' => Role::find($role_id)->name,
                        'action_by' => $user_id
                    ]
                );

                //send confirmation email to requester
                Mail::to(Auth::user())->send(new enrollmentRequestConfirmation($eventUser));

                //send notification email to approver(s)
                //get all the roles that have the *add-to-event permission
                $to_email = $eventUser->event->getUsers(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events']);

                //if there is no one who can add the person, send to site help email with notice
                if ($to_email->isEmpty()) {
                    $eventUser->no_approvers = trans('alerts.frontend.eventuserrequest.no_approvers');
                    Mail::to(Session::get('site_email'))->send(new enrollmentRequestSubmit($eventUser));
                } //else send notice to those who can add to event
                else {
                    Mail::to($to_email)->send(new enrollmentRequestSubmit($eventUser));
                }

                // Redirect to main dashboard on successful enrollment request
                return redirect()->route('frontend.user.dashboard')
                    ->with('success', (trans('alerts.frontend.eventuserrequest.success',
                        ['date' => $eventUser->event->DisplayDateStartEndTimes])));
            }
        }
    }

    /**
     * Pending Enrollments
     * Sends Data Table to client
     */
    public function pending() {
        return view('courseInstance.events.request.pending-event-request');
    }

    
    /**
     * Add Requested user to the event
     */
    public function add($id) {
        $user = Auth::user();
        $now = Carbon::now()->timezone(session('timezone'))->format('Y-m-d H:i:s');

        $request = EventRequest::find($id);
        
        $request->approved_by = $user->id;
        $request->approved_on = $now;
        $request->update();

        // To Add User to Event Page and add the enrollment ID as querystring so we can grab at EventUsersController@users
        return redirect()->route('event.user.add', ['id' => $request->event_id, 'enroll_id' => $id]);
    }

    /**
     * Waitlist the request user to the event
     */
    public function waitlist($id) {
        $user = Auth::user();
        $now = Carbon::now()->timezone(session('timezone'))->format('Y-m-d H:i:s');

        $request = EventRequest::find($id);
        
        $request->waitlisted_by = $user->id;
        $request->waitlisted_on = $now;
        $request->update();

        return redirect()->route('pending');
    }

    /**
     * deny the event request
     */
    public function deny($id) {
        $user = Auth::user();
        $now = Carbon::now()->timezone(session('timezone'))->format('Y-m-d H:i:s');

        $request = EventRequest::find($id);
        
        $request->denied_by = $user->id;
        $request->denied_on = $now;
        $request->update();

        return redirect()->route('pending');
    }

        
    public function requestTableData(Request $request) {


        $events = Event::has('eventRequest')->get()->reject(function ($event) {
            return $event->hasSiteCourseEventPermissions('add-people-to-events','course-add-people-to-events','event-add-people-to-events') == false;
        });
        $data = EventRequest::where('approved_by', null)
            ->where('denied_by', null)
            ->where('waitlisted_by', null)
            ->whereIn('event_id', $events->pluck('id'))
            ->orderBy('created_at')
            ->get();
        
        foreach ($data as &$d) {                      
            // Build columns for Data Table
            $d->name = $d->user->first_name .' '. $d->user->last_name .' ('. $d->user->email .')';
            $d->course = $d->event->CourseInstance->Course->abbrv;
            $d->event_date = \Carbon\Carbon::parse($d->event->start_time)->format('m/d g:i a');
            $d->where = $d->event->initialMeetingRoom->location->building->abbrv. ' - ' .$d->event->initialMeetingRoom->location->abbrv . ' ' . $d->event->InitialMeetingRoom->abbrv;            
            $d->role_name = $d->role->name;
            $d->requested_time = \Carbon\Carbon::parse($d->created_at)->timezone(session('timezone'))->format('m/d g:i a');       
        }

        return DataTables::of($data)->addColumn('actions', function($d) {
            return $d->action_buttons;
        })
        ->addColumn('comments', function($d) {
            return $d->comments();
        })
        ->rawColumns(['actions', 'comments'])->make(true);
    }

    /**
     * Lookup to see if selected role is a learner role via Ajax
     *
     */
    public function isLearnerRole(Request $request)
    {
        $role_id = $request->role_id;
        $role = Role::find($role_id);

        if ($role->learner == 1)
        {
            $isLearnerRole = true;
        }
        else
        {
            $isLearnerRole = false;
        }

        return response()->json(
            [
                'isLearnerRole' => $isLearnerRole
            ]
        );
    }

    /**
     * Lookup the fee amount for selected fee type via Ajax
     *
     */
    public function lookupFee(Request $request)
    {
        $fee_id = $request->fee_id;
        $fee_amount = CourseFees::find($fee_id);

        return response()->json(
            [
                'fee_amount' => $fee_amount->amount
            ]
        );
    }

    /**
     * Verify coupon via Ajax
     *
     */
    public function checkCoupon(Request $request)
    {
        $coupon_code = $request->coupon_code;
        $courseCoupon = CourseCoupons::where('coupon_code', $coupon_code)
                        ->where(function($q) {
                            $q->where('expiration_date', '>=', date("Y-m-d"))
                                ->orWhereNull('expiration_date');
                        })
                        ->first();

        if ($courseCoupon)
        {
            $isValid = true;
            $amount = $courseCoupon->amount;
            $type = $courseCoupon->type;
        }
        else
        {
            $isValid = false;
            $amount = 0;
            $type = null;
        }

        return response()->json(
            [
                'is_valid' => $isValid,
                'amount' => $amount,
                'type' => $type
            ]
        );
    }

    //Get Auth.NET JSON Token

    public function getAuthNetToken(Request $request)
    {

        $user = Auth::user();
        $site = Site::find(Session::get('site_id'));
        $url_root = "http://" . $site->url_root;

        $eventUserPayment = EventUserPayment::find($request->event_user_payment_id);

        $authorize_payment_api_login_id = $site->getSiteOption(9);
        $authorize_payment_transaction_key = $site->getSiteOption(10);

        /* Create a merchantAuthenticationType object with authentication details
              retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($authorize_payment_api_login_id);
        $merchantAuthentication->setTransactionKey($authorize_payment_transaction_key);

        // Set the transaction's refId
        //$refId = 'ref' . time();
        $refId = $eventUserPayment->id;

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user->id);
        $customerData->setEmail($user->email);

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($user->first_name);
        $customerAddress->setLastName($user->last_name);
        $customerAddress->setPhoneNumber($user->phone);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($eventUserPayment->id);
        $order->setDescription($eventUserPayment->eventUser->event->DisplayEventNameShort);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($eventUserPayment->amount_after_coupon);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->setOrder($order);

        // Set Hosted Form options
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedPaymentButtonOptions");
        $setting1->setSettingValue("{\"text\": \"Pay\"}");

        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedPaymentOrderOptions");
        $setting2->setSettingValue("{\"show\": false}");

        $setting3 = new AnetAPI\SettingType();
        $setting3->setSettingName("hostedPaymentReturnOptions");
        $setting3->setSettingValue(
            "{\"url\": \"". $url_root. "/paymentComplete/". $eventUserPayment->id ."\", \"cancelUrl\": \"". $url_root. "/payment/". $eventUserPayment->id ."\", \"showReceipt\": false}"
        );

        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedPaymentCustomerOptions");
        $setting4->setSettingValue("{\"showEmail\": true, \"requiredEmail\": false, \"addPaymentProfile\": true}");

        // Build transaction request
        $request = new AnetAPI\GetHostedPaymentPageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        $request->addToHostedPaymentSettings($setting1);
        $request->addToHostedPaymentSettings($setting2);
        $request->addToHostedPaymentSettings($setting3);
        $request->addToHostedPaymentSettings($setting4);

        //execute request
        $controller = new AnetController\GetHostedPaymentPageController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            echo $response->getToken()."\n";
        } else {
            echo "ERROR :  Failed to get hosted payment page token\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "RESPONSE : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
        }
        return $response;

    }
}