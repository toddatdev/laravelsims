@extends('frontend.layouts.app')

@section('content')

    <h1>{{ $site->abbrv }}</h1>
    <p>{{ $site->name }}</p>
    <p>{{ $site->organization_name }}</p>
    <p>{{ $site->email }}</p>
    <ul>
        @foreach($site->courses as $course)
            <li>{{ $course->abbrv }}</li>
        @endforeach
    </ul>

@stop