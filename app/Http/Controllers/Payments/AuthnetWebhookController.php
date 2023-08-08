<?php

namespace App\Http\Controllers\Payments;

use App\Events\Frontend\Event\UserAddedToEvent;
use App\Models\Access\User\User;
use App\Models\CourseInstance\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payments\AuthnetWebhook;
use App\Models\Payments\AuthNetTransactions;
use App\Models\CourseInstance\EventUserPayment;
use App\Models\CourseInstance\EventUser;
use App\Models\Access\Role\Role;
use App\Models\CourseInstance\EventUserHistory;
use App\Models\Site\Site;
use Mail;
use Session;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;


class AuthnetWebhookController extends Controller
{
    public function webhookListener(Request $request){

        //date and path for logging
        $date = new Carbon($request->get('date', today()));
        $filePath = storage_path("logs/laravel-{$date->format('Y-m-d')}.log");

        //log that we got to webhook
//        $log = ['description' => 'entered function'];
//        $webhookLog = new Logger('webhook');
//        $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//        $webhookLog->info('WebHookLog', $log);

        $site_id = Session::get('site_id');
        //log site id from session
//        $log = ['site_id' => $site_id, 'description' => 'site id from session'];
//        $webhookLog = new Logger('webhook');
//        $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//        $webhookLog->info('WebHookLog', $log);

        $site = Site::find($site_id);
//        $log = ['site_id' => $site->id, 'description' => 'site id from model'];
//        $webhookLog = new Logger('webhook');
//        $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//        $webhookLog->info('WebHookLog', $log);

        //get Auth.NET signature key from site_options
        $authorize_payment_api_login_id = $site->getSiteOption(9);
        $authorize_payment_transaction_key = $site->getSiteOption(10);
        $authorize_payment_signature_key = $site->getSiteOption(12);

//        $log = ['signature_key_from_db' => $authorize_payment_signature_key, 'description' => 'signature key from db'];
//        $webhookLog = new Logger('webhook');
//        $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//        $webhookLog->info('WebHookLog', $log);

        //these are for the EVMS Sandbox (user: 2021EvmsSandbox, pw: SimsRocks!) leaving here for reference
//        $authorize_payment_api_login_id = '8auP665T3';
//        $authorize_payment_transaction_key = '6tVtzFeb95PS693D';
//        $authorize_payment_signature_key = '7BF9C2A3A7E603F8F197D1A9F89FC8FD9FDD88513CB2A80F7450AD6A0B34920F0336B364BCCE3A844FEE23A54B464679643CEFFED8287C1846BB0BF55B16AFAC';

        // getallheaders() is php standard function.
        $headers = getallheaders();

        //log headers
//        $log = ['headers' => $headers, 'description' => 'headers'];
//        $webhookLog = new Logger('webhook');
//        $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//        $webhookLog->info('WebHookLog', $log);

        $payload = $request->getContent(); // for laravel

        //log payload
//        $log = ['payload' => $payload, 'description' => 'payload'];
//        $webhookLog = new Logger('webhook');
//        $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//        $webhookLog->info('WebHookLog', $log);

        // initialization of Model class
        $webhook = new AuthnetWebhook($authorize_payment_signature_key, $payload, $headers);

        // check the valid signature and payload data.

        if ($webhook->isValid()) {

//            $log = ['valid_webhook' => 'true', 'description' => 'valid_webhook'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

            // Access notification values
            $payload_json_obj = json_decode($payload);

            // Get the authorized.net transaction ID, Event Type, Ref ID
            $transactionId = $payload_json_obj->payload->id;
            $eventType = $payload_json_obj->eventType; // net.authorize.payment.authcapture.created
            $merchantRefId = $payload_json_obj->payload->merchantReferenceId; // this is our $eventUserPayment->id passed to them with payment
            $responseCode = $payload_json_obj->payload->responseCode; // should always be 1 for successful transactions because the webhook doesn't pass back failures

//            $log = ['transactionId' => $transactionId, 'description' => 'transactionId'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

//            $log = ['eventType' => $eventType, 'description' => 'eventType'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

//            $log = ['ref_id' => $merchantRefId, 'description' => 'ref_id'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

//            $log = ['response_code' => $responseCode, 'description' => 'response_code'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

            //Save Payload to DB (auth_net_transactions table)
            $authNetTransaction = AuthNetTransactions::create([
                'event_user_payment_id' => $merchantRefId,
                'transaction_id' => $transactionId,
                'type' => $eventType,
                'payload' => $payload,
                'exception' => $responseCode //todo: we can probably remove this field from table, not really needed
            ]);

            //log $authNetTransaction->id just created
//            $log = ['auth_net_transactions' => $authNetTransaction->id, 'description' => 'auth_net_transactions.id'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

            //Update event_user_payment table to set transaction_successful = 1
            $eventUserPayment = EventUserPayment::where('id', '=', $merchantRefId)->first();
            $eventUserPayment->update(['transaction_successful' => 1]);

            //log $eventUserPayment->id to make sure it was found to update
//            $log = ['event_user_payment_id' => $eventUserPayment->id, 'description' => 'eventUserPayment transaction_successful'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

            $eventUser = EventUser::find($eventUserPayment->event_user_id);

            //log $eventUser->id to make sure it was found to update
//            $log = ['$eventUser->id' => $eventUser->id, 'description' => 'found eventUser'];
//            $webhookLog = new Logger('webhook');
//            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
//            $webhookLog->info('WebHookLog', $log);

            //if auto register course
            if ($eventUser->event->courseInstance->course->isOptionChecked(1)) {
                $status_id = 1; //Enrolled for event_user
                $action_id = 2; //Enrolled Action for History
            } else {
                $status_id = 3; //Waitlist for event_user
                $action_id = 5; //Request Access for History
            }

            //update status to enroll or waitlist
            $eventUser->update(['status_id' => $status_id]);

            //send email if enrolled
            if($status_id == 1)
            {
                event(new UserAddedToEvent(User::find($eventUser->user_id), Event::find($eventUser->event_id), Role::find($eventUser->role_id)));
            }

            //add payment to event_user_history
            EventUserHistory::create(
                [
                    'event_user_id' => $eventUser->id,
                    'action_id' => 10,
                    'display_text' => 'Payment Approved',
                    'action_by' => $eventUser->user_id
                ]
            );

            //add enrollment request to event_user_history
            EventUserHistory::create(
                [
                    'event_user_id' => $eventUser->id,
                    'action_id' => $action_id,
                    'display_text' => Role::find($eventUser->role_id)->name,
                    'action_by' => $eventUser->user_id
                ]
            );

//            $this->AuthWebhookTestEmail($transactionId, 'Webhook Triggered. Transaction id is found.');

        } else {

            //log invalid webhook
            $log = ['valid_webhook' => 'false', 'description' => 'valid_webhook'];
            $webhookLog = new Logger('webhook');
            $webhookLog->pushHandler(new StreamHandler($filePath), Logger::INFO);
            $webhookLog->info('WebHookLog', $log);

//            $this->AuthWebhookTestEmail('Empty', 'Webhook Triggered. Transaction id is not found.');
            $response = 'error';

        }

        //adding 200 status here because it's required in return to Auth.NET
        return response()->json([],200);

    }

    public function AuthWebhookTestEmail($transaction_id, $status){

        //mitcks: this is just a function that was used for testing, leaving here in case needed later but calls to it are commented out

        $email_data = array (
            'to' => 'ksm5pitt@gmail.com',
            'to_name' =>  'Kim Mitchell',
            'subject' => 'auth webhook triggered email',
            'from_email' => 'info@12dot6.com',
            'from_name' => '12dot6'
        );

        $email_data['email_content'] = 'Transaction Id: '.$transaction_id.' <br/> Status: '.$status.' <br/>';

        Mail::send([], $email_data, function($message)  use ($email_data) {

            $message->to($email_data['to'] , $email_data['to_name'])
                ->subject($email_data['subject']);

            $message->from($email_data['from_email'] ,$email_data['from_name']);
            $message->setBody($email_data['email_content'], 'text/html');

        });

    }


    //This function is "pinged" by the Payment Confirmation page to see if the webhook
    // has returned data yet and then redirects the user to event dashboard
    // it is because there can be a slight lag between when the user clicks Continue in the
    // Auth.NET page after success and receiving confirmation from webhook
    public function webhookCompletionCheck (Request $request)
    {
        $authNetTransaction = AuthNetTransactions::where('event_user_payment_id', "=", $request->event_user_payment_id)->first();

        if($authNetTransaction != null)
        {
            $eventUserPayment = EventUserPayment::find($authNetTransaction->event_user_payment_id);

            return response()->json(
                [
                    'success' => true,
                    'redirect_url' => '/courseInstance/events/event-dashboard/'. $eventUserPayment->eventUser->event_id
                ]
            );
        }
        else
        {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
}
