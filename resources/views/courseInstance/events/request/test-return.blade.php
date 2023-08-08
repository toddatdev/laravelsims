@extends('frontend.layouts.public')

{{--change title if auto enroll--}}

@section('page-header')
    <h1>
        {{--change heading text if auto enroll--}}
    </h1>
@endsection

@section('content')

<form method="post" action="https://test.authorize.net/payment/payment" id="formAuthorizeNetTestPage" name="formAuthorizeNetTestPage">

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">

                        </div>
                    </div>
                    <div class="card-body">


                    </div>

                </div>
            </div>
        </div>
    </section>

</form>

@endsection

@section('after-scripts')

    <script>

        //required for Ajax functionality
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            //set auth.net token
            $.ajax({
                url: '{{ url('evms-authnet-webhook-listener') }}',
                method: 'post',
                data: {
                    {{--event_user_payment_id: {{ $eventUserPayment->id }}--}}
                },
                success:function(response){

                    console.log(response);
                    // $('#token').val(response);

                },
                error:function(error){
                    // console.log(error)
                }
            });

        });

    </script>

@endsection