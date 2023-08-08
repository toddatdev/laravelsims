@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('content')
    <!-- Single button -->
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            Jump to... <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($menuCourseContent as $menuItem)
                <li>
                    <a href="#">{{$menuItem->menu_title}}</a>
                    @foreach($menuItem->unPublishedContentItems as $subMenuItem)
                        <a href="/course/content/page/{{ $subMenuItem->id }}/preview">&nbsp;&nbsp;&nbsp;{{$subMenuItem->menu_title}}</a>
            @endforeach
            <li role="separator" class="divider"></li>
            </li>
            @endforeach
        </ul>
    </div>
    <h1>{{ $item['module'] }}</h1>
    <h2>{{ $item['menu_title'] }}</h2>
    <hr>
    <div class="text-center mt-5 pt-3">
        @isset($courseFile)
            @if(strtolower(getFileExtension($courseFile->links ?? '')) === 'mp4')
                <video width="720" height="380" controls style="margin: 0 auto;">
                    <source src="{{$courseFile->links ?? ''}}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @elseif(strtolower(getFileExtension($courseFile->links ?? ''))   === 'pdf')
                <iframe width="730px" height="1000px" class="doc"
                        src="{{$courseFile->links}}" frameborder="0"></iframe>
            @elseif(isOfficeExtension($courseFile->links))
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{$courseFile->links}}"
                        width="700" height="780" style="border: none;"></iframe>
            @else
                <a class="btn btn-default" href="{{$courseFile->links}}">Download File</a>
            @endif
        @endisset
    </div>
    @if(!empty($page->text?? ''))
        {!!  $page->text !!}
    @endif
    <div class="row ml-20 mt-20">
        <hr>
        <div class="pull-left ml-20">
            @isset($prev['content_id'])
                <a href="{{$prev['content_id']}}">
                    <button class="btn btn-info">Previous</button>
                </a>
            @endisset
        </div>
        <div class="pull-right mr-40">
            @isset($next['content_id'])
                <a href="{{$next['content_id']}}">
                    <button class="btn btn-info">Next</button>
                </a>
            @endisset
        </div>
    </div>
@endsection