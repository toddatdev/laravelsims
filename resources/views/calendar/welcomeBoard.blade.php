
@extends(($darkBackground) ? 'frontend.layouts.publicNoWrapperDark' : 'frontend.layouts.publicNoWrapper')

@section('content')
    <section class="content ($darkBackground) ? 'bg-dark' ">
        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <p class="h3">
                        <small
                            @if(!$darkBackground)
                                class="text-muted"
                            @endif
                        >{!! trans('strings.frontend.welcome_to_heading') !!}</small>
                    </p>
                    <p class="display-4">
                        {{ $siteAbbrv }}
                    </p>
                </div>
                <div class="float-right">
                    <p class="h3">
                        <small
                            @if(!$darkBackground)
                                class="text-muted"
                            @endif
                        >{{ $currentDate }}</small>
                    </p>
                    <p class="display-4" id='clock'></p>
                </div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body
                @if($darkBackground)
                    bg-dark
                @endif
                ">
                <p class="h5 text-bold">{!! trans('strings.frontend.todays_events') !!}</p>
                <span id="eventData"></span>
            </div>
        </div>

    </section>


@endsection

@section('after-scripts')

    <script>

        function updateEvents() {
            $.ajax({
                url: "/welcomeData" + '/' + "{{$locations}}",
                type: "GET",
                data : {"_token": "{{ csrf_token() }}"},
                success: function (data) {
                    $("#eventData").html(data);
                },
            });
        };

        function updateClock ( )
        {
            var currentTime = new Date ( );

            var currentHours = currentTime.getHours ( );
            var currentMinutes = currentTime.getMinutes ( );

            // Pad the minutes and seconds with leading zeros, if required
            currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;

            // Choose either "AM" or "PM" as appropriate
            var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

            // Convert the hours component to 12-hour format if needed
            currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

            // Convert an hours component of "0" to "12"
            currentHours = ( currentHours == 0 ) ? 12 : currentHours;

            // Compose the string for display
            var currentTimeString = currentHours + ":" + currentMinutes + " " + timeOfDay;

            $("#clock").html(currentTimeString);
        }

        $(document).ready(function()
        {
            //load them initially
            updateClock();
            updateEvents();

            //then update automatically on interval
            setInterval('updateClock()', 1000); //1 second
            setInterval('updateEvents()', 60000); //60 seconds

        });
    </script>

@endsection
