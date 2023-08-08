@extends('frontend.layouts.app')

@section('content')

    <div id="calendar">

        <div class="panel-body" id="dpWeek">
        </div>


        <!-- turn events into json for calendar.js -->
        <script>
            var inputWeek = "{{ $inputWeek }}";
            var weekStartDate = "{{ $weekStartDate }}";
            var weekEvents = @json($weekEvents);
            initWeek();           
        </script>

    </div>

@stop
