@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('content')

    <!-- Single button -->
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Jump to... <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($courseContent as $menuItem)
            <li>
                <a href="#">{{$menuItem->menu_title}}</a>
                @foreach($menuItem->publishedContentItems as $subMenuItem)
                    <a href="/course/content/page/{{ $subMenuItem->id }}">&nbsp;&nbsp;&nbsp;{{$subMenuItem->menu_title}}</a>
                @endforeach
                <li role="separator" class="divider"></li>
            </li>
            @endforeach
        </ul>
    </div>
    <h1>{{ $item['module'] }}</h1>
    <h2>{{ $item['menu_title'] }}</h2>
    <hr>
    {!!  $page->text !!}

    <div class="row ml-20 mt-20">
        <hr>
        <div class="pull-left ml-20">
            @isset($prev['content_id'])
                <a href="{{$prev['content_id']}}">
                    <button class="btn btn-info">Previous</button>
                </a>
            @endisset
        </div>
        <div class="pull-right mr-40" >
            @isset($next['content_id'])
                <a href="{{$next['content_id']}}">
                    <button class="btn btn-info">Next</button>
                </a>
            @endisset
        </div>
    </div>


@endsection

@section('after-scripts')

@stop
