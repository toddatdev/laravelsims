@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.courseCoupons.title'))

@section('before-scripts')
    {{ Html::script("js/jquery.js") }}
@endsection

@section('page-header')
    <h4>
        {{ trans('menus.backend.courseCoupons.title') }}
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
                             @include('courses.courseCoupons.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- html generated in app/DataTables/CourseCouponsDataTable.php--}}
                        {{$dataTable->table(['id' => 'CourseCoupons'])}}
                        <p class="mt-2"><i class="fas fa-question-circle fa-lg text-secondary"></i> {{ trans('strings.backend.courseCoupons.amount_help') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('after-scripts')

    {{-- These need to be included here in after script section so they load after the DataTables.js file in backend.js--}}
    {{ Html::script("/js/moment.js") }}
    {{ Html::script("js/DataTables/Editor/dataTables.editor.js") }}
    {{ Html::style("/css/DataTables/Editor/editor.dataTables.css") }}
    {{ Html::script("js/DataTables/DateTime-1.0.3/js/dataTables.dateTime.js") }}
    {{ Html::style("js/DataTables/DateTime-1.0.3/css/dataTables.dateTime.css") }}

    <script type="text/javascript">

        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            // Activate an inline edit on click of a table cell
            // limited to columns with 'className' => 'editable'
            // added this className so when button clicked to activate/deactivate it does not cause JS error
            // $('#CourseCoupons').on( 'click', 'tbody td.editable', function (e) {
            //     editor.inline( this );
            // } );

            $('#CourseCoupons').on( 'click', 'tbody td:not(:first-child)', function (e) {
                editor.inline( this, {
                    onBlur: 'submit'
                } );
            } );

            var editor = new $.fn.dataTable.Editor({
                ajax: "/courseCoupons",
                table: "#CourseCoupons",
                fields: [
                    {
                        label: "Course ID:",
                        name: "course_id",
                        def: "{{ Request()->course_id }}",
                        type: "hidden"
                    },
                    {
                        label: "{{trans('labels.courseFees.coupon_code')}}",
                        name: "coupon_code"},
                    {
                        label: "{{trans('labels.courseFees.amount')}}",
                        name: "amount"},
                    {
                        label: "{{trans('labels.courseFees.type')}}",
                        name: "type",
                        type:  "select",
                        options: [
                            { label: "{{trans('labels.courseFees.amount')}}",  value: 'V' },
                            { label: "{{trans('labels.courseFees.percent')}}", value: 'P' }
                        ],
                    },
                    {
                        label: "{{trans('labels.courseFees.expiration_date')}}",
                        name: "expiration_date",
                        type:  'datetime',
                        def:   function () { return new Date();}
                    }
                ]
            });

            {{-- JS generated in app/DataTables/CourseFeeTypesDataTable.php--}}
            {{$dataTable->generateScripts()}}

        });

    </script>

@endsection



