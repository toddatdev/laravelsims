{{--A way to change the log file date--}}
<form action="{{ route('logs') }}">
    <input type="date" name="date" value="{{ $date ?  $date->format('Y-m-d') : today()->format('Y-m-d') }}">
    <button type="submit">Get</button>
</form>

{{--View log file info and contents--}}
@if(empty($data['file']))
    <div>
        <h3>No Logs Found</h3>
    </div>
@else
    <div>
        <h5>Updated on: {{ $data['lastModified']->format('Y-m-d h:i a') }}</h5>
        <h5>File Size: {{ round($data['size'] / 1024) }} KB</h5>
        <pre>{{ $data['file'] }}</pre>
    </div>
@endif

