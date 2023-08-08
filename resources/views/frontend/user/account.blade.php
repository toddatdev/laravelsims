@extends('frontend.layouts.app')

@section('content')

    @if (app('request')->input('toolarge'))
        <div class="alert alert-danger">
            {{ trans('alerts.frontend.profile.image_size') }}
        </div>
    @endif

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.user.account') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="card-header p-0 pt-1">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item">
                                    <a class="nav-link active" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.profile') }}</a>
                                </li>

                                <li role="presentation" class="nav-item">
                                    <a class="nav-link" href="#edit" aria-controls="edit" role="tab" data-toggle="tab">{{ trans('labels.frontend.user.profile.update_information') }}</a>
                                </li>

                                @if ($logged_in_user->canChangePassword())
                                    <li role="presentation" class="nav-item">
                                        <a class="nav-link" href="#password" aria-controls="password" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.change_password') }}</a>
                                    </li>
                                @endif
                            </ul>

                            <!-- tabs -->
                            <div class="tab-content">
                                <!-- view profile -->
                                <div role="tabpanel" class="tab-pane mt-30 active" id="profile">
                                    @include('frontend.user.account.tabs.profile')
                                </div>

                                <!-- edit information -->
                                <div role="tabpanel" class="tab-pane mt-30" id="edit">
                                    @include('frontend.user.account.tabs.edit')
                                </div>

                                <!-- change password -->
                                @if ($logged_in_user->canChangePassword())
                                    <div role="tabpanel" class="tab-pane mt-30" id="password">
                                        @include('frontend.user.account.tabs.change-password')
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('after-styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('/css/user_profile_update.css') }}">
@endsection


@section('after-scripts')
    <script type="text/javascript" src="{{ asset('/js/user_profile_update.js') }}?{{ time() }}"></script>
@endsection


