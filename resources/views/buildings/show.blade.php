@extends('frontend.layouts.app')

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $building->name }}</h3>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr><th style="width:175px;">{{ trans('labels.buildings.id') }}</th><td>{{ $building->id }}</td></tr>
                            <tr><th>{{ trans('labels.buildings.abbrv') }}</th><td>{{ $building->abbrv }} (<a href="{{ $building->map_url }}" target="_new">{{ trans('labels.buildings.map') }}</a>)</td></tr>
                            <tr><th>{{ trans('labels.buildings.more_info') }}</th><td>{!!html_entity_decode($building->more_info) !!}</td></tr>

                            <tr><th>{{ trans('labels.buildings.street_address') }}</th><td>{{ $building->address }}</td></tr>
                            <tr><th>{{ trans('labels.buildings.city') }}</th><td>{{ $building->city }}</td></tr>
                            <tr><th>{{ trans('labels.buildings.state') }}</th><td>{{ $building->state }}</td></tr>
                            <tr><th>{{ trans('labels.buildings.postal_code') }}</th><td>{{ $building->postal_code }}</td></tr>
                            <tr><th>{{ trans('labels.buildings.display_order') }}</th><td>{{ $building->display_order }}</td></tr>
                            <tr><th>{{ trans('labels.buildings.timezone') }}</th><td>{{ $building->timezone }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop