@extends('frontend.layouts.modal')

<style>
    @media screen {
        #printSection {
            display: none;
        }
    }

    @media print {
        body * {
            visibility:hidden;
        }
        #printSection, #printSection * {
            visibility:visible;
        }
        #printSection {
            position:absolute;
            left:96px;
            top:96px;
            width: 600px;
        }
    }
</style>

<div class="modal-header text-center">
    <h5 class="modal-title w-100 text-md">{{ $eventUserPayment->eventUser->event->DisplayEventNameShort }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></button>
</div>

<div class="modal-body">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" id="heading">{{ trans('labels.event.view_receipt') }}</h3>
        </div>
        <div class="card-body">
            <div id="printThis" >
                <div class="row mb-2">
                    {{ Form::label('transaction_id', trans('labels.courseFees.merchant'), ['class' => 'col-md-4 control-label text-md-right']) }}
                    <div class="col-md-8 text-left">
                        {{ $site->name }} ({{ $site->abbrv }})
                    </div>
                </div>
                <div class="row mb-2">
                    {{ Form::label('transaction_id', trans('labels.courseFees.billing_name'), ['class' => 'col-md-4 control-label text-md-right']) }}
                    <div class="col-md-8 text-left">
                        {{ $response->getTransaction()->getBillTo()->getFirstName() }} {{ $response->getTransaction()->getBillTo()->getLastName() }}
                    </div>
                </div>
                <div class="row mb-2">
                    {{ Form::label('transaction_id', trans('labels.courseFees.description'), ['class' => 'col-md-4 control-label text-md-right']) }}
                    <div class="col-md-8 text-left">
                        {{ $response->getTransaction()->getOrder()->getDescription() }}
                    </div>
                </div>
                <div class="row mb-2">
                    {{ Form::label('transaction_id', trans('labels.courseFees.payment_date'), ['class' => 'col-md-4 control-label text-md-right']) }}
                    <div class="col-md-8 text-left">
                        {{ date_format($response->getTransaction()->getSubmitTimeLocal(), 'm-d-Y g:i:s a') }}
                    </div>
                </div>
                <div class="row mb-2">
                    {{ Form::label('transaction_id', trans('labels.courseFees.transaction_ID'), ['class' => 'col-md-4 control-label text-md-right']) }}
                    <div class="col-md-8 text-left">
                        {{ $response->getTransaction()->getTransId() }}
                    </div>
                </div>
                <div class="row mb-2">
                    {{ Form::label('transaction_id', trans('labels.courseFees.amount'), ['class' => 'col-md-4 control-label text-md-right']) }}
                    <div class="col-md-8 text-left">
                        ${{ number_format($response->getTransaction()->getAuthAmount(), 2) }}
                    </div>
                </div>
                @if ($response->getTransaction()->getPayment()->getCreditCard() != null)
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.payment_method'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ trans('labels.courseFees.credit_card') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.card_type'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ $response->getTransaction()->getPayment()->getCreditCard()->getCardType() }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.last_4'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ $response->getTransaction()->getPayment()->getCreditCard()->getCardNumber() }}
                        </div>
                    </div>
                @endif
                @if ($response->getTransaction()->getPayment()->getBankAccount() != null)
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.payment_method'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ trans('labels.courseFees.e_check') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.routing_number'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ $response->getTransaction()->getPayment()->getBankAccount()->getRoutingNumber() }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.account_number'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ $response->getTransaction()->getPayment()->getBankAccount()->getAccountNumber() }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{ Form::label('transaction_id', trans('labels.courseFees.name_on_account'), ['class' => 'col-md-4 control-label text-md-right']) }}
                        <div class="col-md-8 text-left">
                            {{ $response->getTransaction()->getPayment()->getBankAccount()->getNameOnAccount() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <div class="float-right">
                <button id="btnPrint" type="button" class="btn btn-success btn-md">{{ trans('buttons.general.print') }}</button>
            </div>
        </div><!-- /.card-footer -->
    </div>
</div>

<script>
    document.getElementById("btnPrint").onclick = function () {
        printElement(document.getElementById("printThis"));
    }

    function printElement(elem) {
        var domClone = elem.cloneNode(true);

        var $printSection = document.getElementById("printSection");

        if (!$printSection) {
            var $printSection = document.createElement("div");
            $printSection.id = "printSection";
            document.body.appendChild($printSection);
        }

        $printSection.innerHTML = "";
        $printSection.appendChild(domClone);
        window.print();
    }
</script>



