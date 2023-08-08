@extends('frontend.layouts.app')
@section ('title', $location->building->abbrv . ' ' . $location->abbrv )

@section('content')

	<section class="content">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							{{ $location->building->name }} ({{ $location->building->abbrv }})
							@if ($location->html_color != "")
								<span style="color:{{$location->html_color}};"><i class="fas fa-circle"></i></span>
							@endif
						</h3>

					</div>
					<div class="card-body">
						{{--display IF data exists in map, more_info or address for building --}}
						@if($location->building->map_url or $location->building->more_info or $location->building->address)
							{{--building address--}}
							@if ($location->building->address)
								<div class="mt-5 mb-5">
									{{ $location->building->address}}<br>
									{{ $location->building->city}}&nbsp;{{ $location->building->state}}&nbsp;{{ $location->building->postal_code}}<br>
								</div>
							@endif
							{{--building map--}}
							@if($location->building->map_url)
								<div class="mt-5 mb-5">
									<a href="{{ $location->building->map_url }}" target="_new">{{ trans('labels.buildings.map') }}</a>
								</div>
							@endif
							{{--building more info--}}
							@if($location->building->more_info)
								@if($location->building->more_info)
									<div class="mt-5 mb-5">
										{!!html_entity_decode($location->building->more_info)!!}
									</div>
								@endif
							@endif
						@else
							{!! trans('strings.frontend.no_building_info', ['siteEmail'=> Session::get('site_email')]) !!}
						@endif
					</div>
				</div>

				{{--location card--}}
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">{{ $location->name }} ({{ $location->abbrv }})</h3>
					</div>
					<div class = "card-body">
						{{--display the location panel id more info or url exist--}}
						@if($location->more_info or $location->directions_url)
							{{--location directions--}}
							@if($location->directions_url != "")
								<div class="mt-5 mb-5">
									<a href="{{ $location->directions_url }}" target="_new">{{ trans('labels.locations.directions') }}</a>
								</div>
							@endif
							{{--location more info--}}
							@if($location->more_info != "")
								<div class="mt-5 mb-5">
									{!! html_entity_decode($location->more_info) !!}
								</div>
							@endif
						@else
							{!! trans('strings.frontend.no_location_info', ['siteEmail'=> Session::get('site_email')]) !!}
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>

@stop