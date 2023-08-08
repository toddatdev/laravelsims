@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.courseFees.title'))

@section('before-scripts')
    {{ Html::script("js/jquery.js") }}
@endsection

@section('page-header')
    <h4>
        {{ trans('menus.backend.courseFees.title') }}
    </h4>
@endsection

@section('content')

    @php
        //setting $course here because it can't be passed into the yajra dataTable index function
        $course = \App\Models\Course\Course::find(Session::get('course_id'));
    @endphp

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ $course->name }} ({{ $course->abbrv }})</h3>
                        <div class="float-right">
                            @include('courses.courseFees.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- html generated in app/DataTables/CourseCouponsDataTable.php--}}
                        {{$dataTable->table(['id' => 'CourseFees'])}}
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@section('after-scripts')

    {{-- These need to be included here in after script section so they load after the DataTables.js file in backend.js--}}
    {{ Html::script("/js/DataTables/Editor/dataTables.editor.js") }}
    {{ Html::style("/css/DataTables/Editor/editor.dataTables.css") }}

    <script type="text/javascript">

        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            // Activate an inline edit on click of a table cell
            // limited to columns with 'className' => 'editable'
            // added this so when button clicked to activate/deactivate it does not cause JS error
            $('#CourseFees').on( 'click', 'tbody td.editable', function (e) {
                editor.inline( this );
            } );

            var editor = new $.fn.dataTable.Editor({
                ajax: "/courseFees",
                table: "#CourseFees",
                fields: [
                    {
                        label: "Course ID:",
                        name: "course_id",
                        def: "{{ Request()->course_id }}",
                        type: "hidden"
                    },
                    {
                        label: "{{trans('labels.courseFees.fee_type')}}",
                        name: "course_fee_type_id",
                        type:  "select",
                        options: [
                            {!! session()->get('course_types_select') !!}
                        ],
                    },
                    {
                        label: "{{trans('labels.courseFees.amount')}}",
                        name: "amount"
                    },
                    {
                        label: "{{trans('labels.courseFees.deposit')}}",
                        name: "deposit",
                        type:  "select",
                        options: [
                            { label: "No",  value: '0' },
                            { label: "Yes", value: '1' },
                        ],
                    }
                ]
            });

            {{-- JS generated in app/DataTables/CourseFeeTypesDataTable.php--}}
            {{$dataTable->generateScripts()}}

            // Activate/Deactivate
            $("body").on("click", "[name='change_activation']", function(e) {

                e.preventDefault();

                //these are set where the change_activation button is created in the CourseFeeTypes model
                var courseFeeTypeId = $(this).data('fee_type_id');
                var action = $(this).data('action');

                // need to set variable for the button here outside of ajax so it can be updated inside the ajax success
                var button = this;

                // console.log('courseFeeTypeId: '+courseFeeTypeId);
                // console.log('action: '+action);

                $.ajax({
                    url: '{{ url('updateCourseFeeActivation') }}',
                    method: 'post',
                    data: {
                        fee_type_id: courseFeeTypeId,
                        action: action
                    },
                    success:function(response){
                        if(response.success){
                            // alert('success');

                            //change image for button and data property (the data property is in case they click again)
                            if(action=='turn_on')
                            {
                                // alert('I need to show yellow button');
                                $(button).html("<i class='fa fa-fw fa-pause text-light'></i>");
                                $(button).data('action', "turn_off");
                                $(button).attr('title', "{{trans('buttons.general.retire')}}")
                                $(button).removeClass('btn-success').addClass('btn-warning');
                            }
                            else
                            {
                                // alert('I need to show green button');
                                $(button).html("<i class='fa fa-fw fa-play'></i>");
                                $(button).data('action', "turn_on");
                                $(button).attr('title', "{{trans('buttons.general.activate')}}")
                                $(button).removeClass('btn-warning').addClass('btn-success');
                            }
                            //alert(response.message) //Message from controller
                        }else{
                            //alert("Error")
                        }
                    },
                    error:function(error){
                        // alert('fail');
                        console.log(error)
                    }
                });
            });


        });

    </script>

@endsection
