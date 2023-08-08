@extends('backend.layouts.app')

@section ('title', trans('navs.backend.auth_net_transactions'))

@section('after-styles')

@stop


@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans('navs.backend.auth_net_transactions')}}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="transactions-table" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('labels.courseFees.transaction_ID') }}</th>
                                <th>{{ trans('labels.reports.name') }}</th>
                                <th>{{ trans('labels.shared.course') }}</th>
                                <th>{{ trans('labels.courseFees.payment_date') }}</th>
                                <th>{{ trans('labels.courseFees.fee_type') }}</th>
                                <th>{{ trans('labels.courseFees.coupon_code') }}</th>
                                <th>{{ trans('labels.courseFees.amount') }}</th>
                                <th>{{ trans('labels.courseFees.receipt') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div id="receiptModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

        <div id="historyModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

    </section>

@endsection


@section('after-scripts')

    <script>

        $(function() {

            $('#transactions-table').DataTable({

                ajax: '{!! url('authNetTransactionsReport.data') !!}',

                columns: [
                    { data: 'transaction_id', name: 'transaction_id' },
                    { data: 'name', name: 'name' },
                    { data: 'course', name: 'course' },
                    { data: 'payment_date', name: 'payment_date' },
                    { data: 'fee_type', name: 'fee_type' },
                    { data: 'coupon_code', name: 'coupon_code' },
                    { data: 'amount', name: 'amount' },
                    { data: 'receipt', name: 'receipt', orderable: false, searchable: false},
                ],
                order: [[ 3, "desc" ]], // sort desc by payment date by default

                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                ],

                language: {searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}", search: ""},
                responsive: true,
            });
        });

        $("#receiptModal").on('show.bs.modal', function (e) {
            var triggerLink = $(e.relatedTarget);
            var id = triggerLink.data("id");

            $(this).find(".modal-body").load("/paymentReceipt/"+id);
        });

        $("#historyModal").on('show.bs.modal', function (e) {
            var triggerLink = $(e.relatedTarget);
            var id = triggerLink.data("id");

            $(this).find(".modal-body").load("/event/users/history/"+id);
        });

    </script>

@endsection