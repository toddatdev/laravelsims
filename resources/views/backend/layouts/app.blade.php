<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('meta_author', 'SimMedical')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        {{-- @langRTL
            {{ Html::style(getRtlCss(mix('css/backend.css'))) }}
        @else
            {{ Html::style(mix('css/backend.css')) }}
        @endif --}}
        {{ Html::style(mix('css/backend.css')) }}

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
          gtag('config', '{{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(5) }}');
        </script>

        @yield('after-styles')

        {{-- 2020-12-03 mitcks: these were from the original boiler plate template, we are --}}
        {{-- not supporting these old browsers so they should no longer be needed--}}
        <!-- Html5 Shim and Respond.js IE8 support of Html5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        {{-- {{ Html::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}--}}
        {{-- {{ Html::script('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js') }}--}}
        <![endif]-->

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
{{--    <body class="layout-top-nav skin-{{ config('backend.theme') }} {{ config('backend.layout') }}">--}}
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">

        @include('backend.includes.header')
            @include('backend.includes.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('includes.partials.logged-in-as')
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    @yield('page-header')
                    {{-- Change to Breadcrumbs::render() if you want it to error to remind you to create the breadcrumbs for the given route --}}
                    {{--  {!! Breadcrumbs::renderIfExists() !!} --}}
                </section>

                <!-- Main content -->
                <section class="content">
                    @include('includes.partials.messages')
                    @yield('content')
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            @include('backend.includes.footer')
        </div><!-- ./wrapper -->

        <script>
           var baseUrl = "{{ url('') }}";
        </script>

        <!-- JavaScripts -->
        @yield('before-scripts')

        {{ Html::script(mix('js/backend.js')) }}

        <!-- 2020-11-17 mitcks: These are for DataTable Buttons, I tried including these in
        mix and they just would not work - I think the issue had to do with the order in which they were loading
        found this workaround -->
        {{ Html::script("/js/jszip/dist/jszip.min.js") }}
        {{ Html::script("/js/vfs_fonts.js") }}

        <!-- 2020-11-17 mitcks: this section is used to set default options for DataTables -->
        <script>
            $.extend( true, $.fn.dataTable.defaults, {

                responsive: true,

                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

                buttons: ['excelHtml5', 'pdf', 'csv', 'copy'],

                dom: '<"top"fB>rt<"bottom"lp><"clear">',

                language: {
                    search: "", searchPlaceholder: "{{ trans('labels.general.search_placeholder') }}",
                    url: "/js/DataTablesLang/{{ config('app.locale') }}.json"
                },
            } );
        </script>

        @yield('after-scripts')

    </body>
</html>