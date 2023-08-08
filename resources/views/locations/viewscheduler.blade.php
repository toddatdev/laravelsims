@extends('backend.layouts.app')

@section('page-header')
    <h1>{{ trans('menus.backend.location.schedulers') }}</h1>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="float-left">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }}) Scheduling Locations</h5>
                        <div class="float-right">
                            @include('locations.partial-header-buttons')
                        </div>
                    </div>
                <div class="card-body">

                    @if ($user->hasPermission('scheduling'))
                        @foreach ($buildings as $building)
                            <h4 class="mt-4">{{ $building->name }}</h4>
                            <?php $locations = \App\Models\Location\Location::where('building_id', $building->id)
                                ->whereNull('retire_date')
                                ->orderby('display_order')
                                ->get(); ?>
                            @foreach ($locations as $location)

                                <div class="ml-5">

                                {{--lookup to see if the location checkbox should be checked--}}
                                <?php $locationChecked = \App\Models\Location\LocationSchedulers::
                                where(['location_id' => $location->id,
                                    'user_id' => $user->id])
                                    ->exists(); ?>

                                @if( $locationChecked )
                                    {{--delete--}}
                                    {{--get the id that needs deleted--}}
                                    <?php $locationSchedulerID = \App\Models\Location\LocationSchedulers::
                                    where(['location_id' => $location->id,
                                        'user_id' => $user->id])
                                        ->first()->id; ?>
                                    {!! Form::open(['method'=>'DELETE', 'style'=>'display:inline-block', 'route'=>['locationSchedulers.destroy', $locationSchedulerID]]) !!}
                                    {{ Form::checkbox('location_id', $location->id, true, ['onClick' => 'this.form.submit()']) }}
                                @else
                                    {{--add--}}
                                    {!! Form::open(array('route' => ['locationSchedulers.store'] , 'style'=>'display:inline-block', 'class' => 'form-inline', 'method' => 'STORE')) !!}
                                    {!! Form::hidden('user_id', $user->id) !!}
                                    {{ Form::checkbox('location_id', $location->id, false, ['onClick' => 'this.form.submit()']) }}
                                @endif

                                {{ Form::close() }}

                                {{ $location->abbrv }} ( {{ $location->name }} )

                                </div>
                            @endforeach {{-- Locations --}}

                        @endforeach {{-- Buildings --}}

                        {{-- Allow them to delete the user from all scheduling ALL locations. --}}
                        <div class="container-fluid">
                           <div class="text-center"><a href="/locations/removeallscheduler/{{ $user->id }}" class="btn bnt-xs btn-danger">{{ trans('buttons.backend.locations.remove_scheduler_all', ['name' => $user->first_name. ' '. $user->last_name]) }}</a></div>
                        </div>

                        @else
                            <div class="alert alert-danger text-center">{{ trans('alerts.backend.users.need_scheduler_permission') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop