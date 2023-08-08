<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\AuthNetTransactions;
use Yajra\DataTables\Facades\DataTables;
use Session;

class TransactionsController extends Controller
{
    public function authNetTransactionsReport()
    {
        return view('sites.reports.authnet-transactions');
    }

    public function authNetTransactionsData()
    {

        //Limit transactions to this site
        $transactions = AuthNetTransactions::join('event_user_payments', 'event_user_payments.id', '=', 'auth_net_transactions.event_user_payment_id')
            ->join('event_user', 'event_user.id', '=', 'event_user_payments.event_user_id')
            ->join('events', 'events.id', '=', 'event_user.event_id')
            ->join('course_instances', 'course_instances.id', '=', 'events.course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->where('courses.site_id', '=', Session::get('site_id'))
            ->select('auth_net_transactions.*');

        return DataTables::of($transactions)
            ->addColumn('transaction_id', function($transactions) {
                return $transactions->transaction_id;
            })
            ->addColumn('name', function($transactions) {
                return $transactions->eventUserPayment->eventUser->user->fullname;
            })
            ->addColumn('course', function($transactions) {
                return "<a href='/courseInstance/events/event-dashboard/" . $transactions->eventUserPayment->eventUser->event->id ."'>" . $transactions->eventUserPayment->eventUser->event->DisplayEventNameShort . "</a>";
            })
            ->addColumn('payment_date', function($transactions) {
                return $transactions->created_at;
            })
            ->addColumn('amount', function($transactions) {
                return $transactions->eventUserPayment->amount_after_coupon;
            })
            ->addColumn('fee_type', function($transactions) {
                return $transactions->eventUserPayment->fee_type_descrp;
            })
            ->addColumn('coupon_code', function($transactions) {
                return $transactions->eventUserPayment->coupon_code;
            })
            ->addColumn('receipt', function($transactions) {
                return "<span class='simptip-position-top simptip-smooth' data-tooltip=" . trans('labels.event.view_receipt') ."'>
                        <button class='btn btn-sm btn-success' data-toggle='modal'
                        data-id='" . $transactions->eventUserPayment->id. "' data-target='#receiptModal'>
                        <i class='fas fa-file-invoice-dollar fa-fw'></i>
                        </button>
                        </span> " . $transactions->eventUserPayment->eventUser->ViewHistoryButton;
            })

            ->rawColumns(['receipt', 'course'])
            ->make(true);
    }
}
