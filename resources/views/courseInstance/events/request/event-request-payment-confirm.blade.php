@extends('frontend.layouts.public')

<style>
    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: 1000;
        top: 20%;
        left: 0px;
        opacity: 0.5;
        filter: alpha(opacity=50);
    }
</style>

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

    <section class="content">
        <div class="overlay">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary"
                     role="status" style="width: 3rem; height: 3rem; z-index: 20;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>

        <!-- here goes the main content -->

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
                        <div class="alert alert-secondary" role="alert">
                            <h5>
                                {{ trans('strings.frontend.payment_waiting_for_webhook') }}
                            </h5>
                        </div>
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

        $(document).ready(function()
        {
            // execute automatically on interval to look for webhook completion
            setInterval('webhookCompletionCheck()', 5000); //5 seconds
        });

        function webhookCompletionCheck() {
            $.ajax({
                url: '{{ url('webhookCompletionCheck') }}',
                method: 'get',
                data: {
                    event_user_payment_id: {{ $eventUserPayment->id }}
                },
                success:function(response){
                    if(response.success){

                        //alert('Success');
                        location.href = response.redirect_url

                    }else{
                        //console.log(response.transaction);
                        //alert("Error")
                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        };


    </script>

@endsection