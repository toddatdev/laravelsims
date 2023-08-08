@extends('frontend.layouts.public')

{{--change title if auto enroll--}}
@if($course->isOptionChecked(1))
    @section ('title', trans('navs.frontend.course.auto_enroll'))
@else
    @section ('title', trans('navs.frontend.course.request_enroll'))
@endif

@section('page-header')
    <h1>
        {{--change heading text if auto enroll--}}
        @if($course->isOptionChecked(1))
            {{ trans('navs.frontend.course.auto_enroll')}}
        @else
            {{ trans('navs.frontend.course.request_enroll')}}
        @endif
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
                            <h4 card-title>{{ $eventUserPayment->eventUser->event->DisplayEventNameShort }}</h4>
                            <h5>{{ $course->name }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>
                            Your fee for this course is <span class='text-bold'>${{ $eventUserPayment->amount_after_coupon }}</span>. Please click the <span class='text-bold'>Pay Online</span> button below to continue to the secure payment page.
                        </p>
                        {!! $paymentPolicy !!}
                        {{-- Hidden Input for Token (set below via ajax/api)--}}
                        <input type="hidden" id="token" name="token" value="" />

                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to('/courses/catalogShow/'.$course->id, trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.event.pay_online'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
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
                url: '{{ url('getAuthNetToken') }}',
                method: 'post',
                data: {
                    event_user_payment_id: {{ $eventUserPayment->id }}
                },
                success:function(response){

                    //console.log(response);
                    $('#token').val(response);

                },
                error:function(error){
                    console.log(error)
                }
            });

        });

    </script>

@endsection