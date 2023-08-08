<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Laravel SIMS 5.4')">
        <meta name="author" content="@yield('meta_author', 'SimMedical')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        {{-- @langRTL
            {{ Html::style(getRtlCss(mix('css/frontend.css'))) }}
        @else
            {{ Html::style(mix('css/frontend.css')) }}
        @endif --}}

        {{ Html::style(mix('css/frontend.css')) }}

        {{-- I added this -jl 2018-03-27 8:58  --}}
        {{ Html::style('css/sims.css') }}

        {{--this is for simple pure css tooltips--}}
        {{ Html::style("/css/simptip.css") }}

        {{-- Add the Google Analytics script -jl 2018-05-08 9:38  --}}
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(5) }}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '{{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(5) }}');
        </script>

        @yield('after-styles')

        <!-- Html5 Shim and Respond.js IE8 support of Html5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
{{--        {{ Html::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}--}}
{{--        {{ Html::script('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js') }}--}}
        <![endif]-->

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>

        <script type="text/javascript" src="{{ asset('/js/global_functions.js') }}"></script>

    </head>

    <body class="hold-transition layout-top-nav" style="font-family:'Source Sans Pro' !important;">
        <div class="wrapper">

{{--            @include('frontend.includes.public_header')--}}

            <div class="content-wrapper">

                <section class="content-header">
                    @yield('page-header')
                </section>

                <!-- Main content -->
                <section class="content">
{{--                    @include('includes.partials.messages')--}}
                    @yield('content')
                </section><!-- /.content -->
            </div>
{{--            @include('includes.partials.footer')--}}

        </div><!-- ./wrapper -->

        <script>
           var baseUrl = "{{ url('') }}";
        </script>
        <!-- Scripts -->
        @yield('before-scripts')
            {{ Html::script(mix('js/frontend.js')) }}

            {{--mitcks 5/31/19: old version below, leaving here in case we need to revert--}}
            {{--<script type="text/javascript" src="{{ asset('/helpers/jquery-1.12.2.min.js') }}"></script>--}}
            
            {{-- TC 2019-11-18 Commenting these out since they conflict with js/Frontend which contains all the bootstrap files
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
                {{ Html::script("https://code.jquery.com/jquery-3.3.1.js") }}
            --}}

        @yield('after-scripts')

        @include('includes.partials.ga')

    </body>
</html>
