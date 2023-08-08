@extends('frontend.layouts.public')

@if($course->isOptionChecked(1)) {{--change title if auto enroll--}}
    @section ('title', trans('navs.frontend.course.auto_enroll'))
@else
    @section ('title', trans('navs.frontend.course.request_enroll'))
@endif

@section('page-header')
    <h1>
        @if($course->isOptionChecked(1)) {{--change heading text if auto enroll--}}
            {{ trans('navs.frontend.course.auto_enroll')}}
        @else
            {{ trans('navs.frontend.course.request_enroll')}}
        @endif
    </h1>
@endsection

@section('content')

    {{ Form::open(['url' => 'event/user/request', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

    {{ Form::hidden('course_id', $course->id) }}

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4 card-title>{{ $course->abbrv }}</h4>
                            <h5>{{ $course->name }}</h5>
                        </div>
                    </div>
                    {{--No events - just display message--}}
                    @if(null == $course->upcomingClassDates())
                        <div class='text-danger'>{{trans('labels.courses.no_upcoming_dates')}}</div>
                        <div style="padding-top: 10px">{{ link_to(URL::previous(), trans('buttons.general.cancel'), ['class' => 'btn btn-danger btn-md']) }}</div>
                    @else
                        <div class="card-body">

                            {{-- TO-DO: How do we disable or reject requests of full classes? -jl --}}
                            <div class="form-group row">
                                {{ Form::label('event_id', trans('labels.enrollment.event'), ['class' => 'col-md-2 control-label required text-md-right']) }}
                                {!! Form::select('event_id', $event_details, $event_id, ['class' => 'form-control col-md-5', 'placeholder' => trans('labels.general.select')]) !!}
                            </div>

                            {{-- ROLE --}}
                            <div class="form-group row">
                                {{ Form::label('role_id', trans('labels.enrollment.role'), ['class' => 'col-md-2 control-label required text-md-right']) }}
                                {{ Form::select('role_id', $roles, null, ['id' => 'role_id', 'class' => 'form-control col-md-5', 'placeholder' => trans('labels.general.select')]) }}
                            </div>

                            {{-- FEE TYPE --}}
                            {{-- cannot use isEmpty here because this is an array not a collection--}}
                            @if(count($array_course_fees) > 1)
                                <div id="div_fee" class="form-group row" style="display:none">
                                    {{ Form::label('fee_type', trans('labels.enrollment.fee_type'), ['class' => 'col-md-2 control-label required text-md-right']) }}
                                    {{ Form::select('course_fee_id', $array_course_fees, null, ['id' => 'course_fee_id', 'class' => 'form-control col-md-5', 'placeholder' => trans('labels.general.select')]) }}
                                </div>
                            @else
                                {{-- this hidden field passes the value on submit when there is only one fee type and select list is hidden --}}
                                {{ Form::hidden('course_fee_id', null, ['id'=>'course_fee_id']) }}
                            @endif

                            {{-- Coupons --}}
                            @if(!$courseCoupons->isEmpty())
                                <div id="div_coupon" class="form-group row" style="display:none">
                                    <div class="input-group mb-3">
                                        {{ Form::label('label_coupon_code', trans('labels.enrollment.coupon_code'), ['id'=>'label_coupon_code', 'class' => 'col-md-2 control-label text-md-right']) }}
                                        {{ Form::text('coupon_code', null, ['id'=>'coupon_code', 'class' => 'form-control col-md-3', 'maxlength' => '25', 'autofocus' => 'autofocus' ]) }}
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="apply_coupon">{{ trans('buttons.general.apply') }}</button>
                                        </div>
                                        {{-- placeholder for coupon success/failure message--}}
                                        {{ Form::label('coupon_message', ' ', ['id'=>'coupon_message', 'class' => 'col-md-2']) }}
                                    </div>
                                </div>
                            @endif

                            {{-- Fee Total --}}
                            <div id="div_total_fee" class="form-group row" style="display:none">
                                {{ Form::label('total_fee', trans('labels.enrollment.total_fee'), ['class' => 'col-md-2 control-label text-md-right']) }}
                                {{ Form::label('total_fee_label', ' ', ['class' => 'col-md-5 control-label', 'id'=>'total_fee_label']) }}
                                {{ Form::hidden('total_fee_value', null, ['id'=>'total_fee_value']) }}
                            </div>

                            {{-- Comments --}}
                            <div class="form-group row" >
                                @if($course->isOptionChecked(1)) {{--automatic registration--}}
                                    <div class="col-md-2"></div>
                                    <div class="col-md-5">{{ trans('strings.frontend.event_request.auto_enroll_info') }}</div>
                                @else
                                    {{ Form::label('comments', trans('labels.enrollment.comments'), ['class' => 'col-md-2 control-label text-md-right']) }}
                                    {{ Form::text('comments', null, ['class' => 'form-control col-md-5', 'maxlength' => '100']) }}
                                @endif
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="float-left">
                                {{ link_to('/courses/catalogShow/'.$course->id, trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                            </div><!--pull-left-->
                            <div class="float-right">
                                {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                            </div><!--pull-right-->
                        </div><!-- /.card-footer -->
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{ Form::close() }}

@endsection

@section('after-scripts')

    <script>

        //required for Ajax functionality
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //setup global variables to recalculate totalFee when type or coupon code changes
        var feeAmount = 0;
        var couponAmount = 0;
        var couponType = 'V'; //P or V
        var couponValue = 0;
        var totalFee = 0;

        $('#role_id').on('change', function() {

            var roleId = this.value ;

            //if this is a course with fees use ajax to lookup role in db to determine if it is a learner role
            //then display the fee select if true
            @if(count($array_course_fees) > 0)

                $.ajax({
                    url: '{{ url('isLearnerRole') }}',
                    method: 'post',
                    data: {
                        role_id: roleId
                    },
                    success:function(response){
                        if(response.isLearnerRole){
                            $('#div_fee').show();

                            @if(count($array_course_fees) == 1)
                                //there is only one fee so just look it up and do not display select list
                                var feeId = {{ key($array_course_fees) }};

                                //display coupon input (if applicable)
                                $('#div_coupon').show();
                                //display total fee label
                                $('#div_total_fee').show();

                                //lookup the only fee and set values
                                $.ajax({
                                    url: '{{ url('lookupFee') }}',
                                    method: 'post',
                                    data: {
                                        fee_id: feeId
                                    },
                                    success:function(response){
                                        feeAmount = response.fee_amount;

                                        //recalculate total fee in case coupon was already applied and then they change fee type
                                        if(couponType == 'V'){
                                            couponAmount = couponValue;
                                        }
                                        else{
                                            couponAmount = feeAmount * (couponValue*.01);
                                        }
                                        totalFee = feeAmount - couponAmount;
                                        $('#total_fee_label').text('$'+ totalFee.toFixed(2));
                                        $('#total_fee_value').val(totalFee);
                                        //this needs to be set here even though the select list is hidden, it still needs passed in submit
                                        $('#course_fee_id').val(feeId);
                                    },
                                    error:function(error){
                                        console.log(error)
                                    }
                                });
                            @endif
                        }
                        else {
                            $('#div_fee').hide();
                            $('#div_coupon').hide();
                            $('#div_total_fee').hide();
                        }
                    },
                    error:function(error){
                        console.log(error)
                    }
                });

            @endif
        });


        $('#course_fee_id').on('change', function() {

            var feeId = this.value ;

            //display coupon input (if applicable)
            $('#div_coupon').show();
            //display total fee label
            $('#div_total_fee').show();

            //lookup the fee value based on selection
            $.ajax({
                url: '{{ url('lookupFee') }}',
                method: 'post',
                data: {
                    fee_id: feeId
                },
                success:function(response){
                    feeAmount = response.fee_amount;

                    //recalculate total fee in case coupon was already applied and then they change fee type
                    if(couponType == 'V'){
                        couponAmount = couponValue;
                    }
                    else{
                        couponAmount = feeAmount * (couponValue*.01);
                    }
                    totalFee = feeAmount - couponAmount;
                    $('#total_fee_label').text('$'+ totalFee.toFixed(2));
                    $('#total_fee_value').val(totalFee);
                },
                error:function(error){
                    console.log(error)
                }
            });

        });

        //check to see if coupon code valid and get amount
        $('#apply_coupon').on('click', function() {

            var couponCode = $('#coupon_code').val();

            $.ajax({
                url: '{{ url('checkCoupon') }}',
                method: 'post',
                data: {
                    coupon_code: couponCode
                },
                success:function(response){

                    if(response.is_valid){
                        //display valid coupon message and recalculate total
                        $('#coupon_message').text('{{ trans('labels.enrollment.coupon_valid') }}');
                        $('#coupon_message').css("color", "green");

                        couponType = response.type;
                        couponValue = response.amount;

                        if(couponType == 'V'){
                            couponAmount = couponValue;
                        }
                        else{
                            couponAmount = feeAmount * (couponValue*.01);
                        }
                        totalFee = feeAmount - couponAmount;
                        $('#total_fee_label').text('$'+ totalFee.toFixed(2));
                        $('#total_fee_value').val(totalFee);
                    }
                    else {
                        //display invalid coupon message and reset coupon_code input to null
                        $('#coupon_message').text(couponCode + '{{ trans('labels.enrollment.coupon_invalid') }}');
                        $('#coupon_message').css("color", "red");
                        $('#coupon_code').val('');

                        couponAmount = 0;
                        //reset fee amount back to fee without coupon applied
                        $('#total_fee_label').text('$'+ feeAmount.toFixed(2));
                        $('#total_fee_value').val(feeAmount);

                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        });

    </script>

@endsection