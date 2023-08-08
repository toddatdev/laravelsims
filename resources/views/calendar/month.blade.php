@extends('frontend.layouts.app')

@section('content')

    <div id="calendar" class="month-calendar">

        <span class="month-content">
            <div class="panel-body" id="dpMonth"></div>
        </span>


        <!-- turn events into json for calendar.js -->
        <script>
            var inputMonth = "{{ $inputMonth }}";
            var monthEvents = @json($monthEvents);
            initMonth();
        </script>

    </div>

@stop