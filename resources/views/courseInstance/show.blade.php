@extends('frontend.layouts.app')

@section('content')

    mitcks: made this view just for testing purposes for now

    course instance id: {{ $courseInstance->id }} <br>
    course id: {{ $courseInstance->course->id }} <br>
    course name: {{ $courseInstance->course->name }} <br>
    course abbrv: {{ $courseInstance->course->abbrv }} <br>
    course public comments: {{ $courseInstance->public_comments }} <br>
    course internal comments: {{ $courseInstance->internal_comments }} <br>
    created by: {{ $courseInstance->created_by }} (we need to go get name here)<br>
    created on: {{ $courseInstance->created_at }}<br>
    last edit by: {{ $courseInstance->last_edited_by }} (we need to go get name here)<br>
    edited on: {{ $courseInstance->updated_at }}<br>

    Information for one or more related events to this course instance counld go here

@stop