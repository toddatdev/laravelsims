<div class="card module-item @if($module->retired_date) retired @endif"
     data-type=""
     data-id="{{$module->id}}"
>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title w-100 mb-0">
            <i class="fa fa-grip-vertical module-handle mr-2" aria-hidden="true"></i>
            <a class="accordion-toggle d-inline-block text-decoration-none"
               data-toggle="collapse"
               href="#collapseItem{{$module->id}}">
                <p class="module-title mb-0" style="font-family: Raleway,serif; font-weight: 700; letter-spacing: .2px;"
                   {{ $module->retired_date  ? "retired"  : ''}}  id="menuTitleAcc{{$module->id}}">
                    {{ $module->menu_title }}
                    <span class="collapse-down-icon">
                        <i class="fa fa-caret-right"></i>
                    </span>
                    <span class="collapse-up-icon hidden">
                        <i class="fa fa-caret-down"></i>
                    </span>
                </p>
            </a>
        </h6>
        <div class="dropdown module-dropdown w-100 text-right">
            @if($module->retired_date)
                <button class="btn btn-link btn-sm">
                    <i class="fa fa-eye-slash"></i>
                </button>
            @endif

            @if($module->published_date)
                <button class="btn btn-link btn-sm">
                    <span class="simptip-position-top simptip-smooth"
                          data-tooltip="{{ trans('labels.course_curriculum.published') }}">
                        <i class="fa fa-check" style="color: green;" aria-hidden="true"></i>
                    </span>
                </button>
            @else
                <button class="btn btn-link btn-sm">
                    <span class="simptip-position-top simptip-smooth"
                          data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                        <a href="/course/content/{{ $module->id }}/publishmodule">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </a>
                    </span>
                </button>
            @endif

            <button class="btn btn-outline-primary dropdownAddCourseContent py-0 bop btn-sm"
                    type="button"
                    id="dropdownAddCourseContent{{ $module->id }}"
                    data-toggle="dropdown"
                    data-parent-id="0"
                    aria-haspopup="true"
                    aria-expanded="true">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right"
                 aria-labelledby="dropdownAddCourseContent{{ $module->id }}"
                 style="width: 250px"
            >
                <a href="#"
                   class="dropdown-item add-page"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $contentTypePage }}"
                   data-parent-id="{{ $module->id }}">
                    <i class="fa fa-file"></i>{{ trans('menus.backend.course.content.module-menu-item.add_page') }}
                </a>
                <a href="#"
                   class="dropdown-item presenter-upload"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $contentTypePresenter }}"
                   data-parent-id="{{ $module->id }}">
                    <i class="fa fa-presentation"></i> {{ trans('menus.backend.course.content.module-menu-item.add_articulate') }}
                </a>
                <a href="#"
                   class="dropdown-item any-file-upload"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $contentTypeAnyFile }}"
                   data-parent-id="{{ $module->id }}">
                    <i class="fa fa-file-download"></i>{{ trans('menus.backend.course.content.module-menu-item.add_downloadable_file') }}
                </a>
                <a href="#"
                   class="dropdown-item document-upload"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $contentTypeDoc }}"
                   data-parent-id="{{ $module->id }}">
                    <i class="fa fa-file-alt"></i>{{ trans('menus.backend.course.content.module-menu-item.add_office_file') }}
                </a>
                <a href="#"
                   class="dropdown-item add-qse"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-menu-title="{{ $module->menu_title }}"
                   data-content-type-id="{{ $contentTypeQSE }}"
                   data-parent-id="{{ $module->id }}">
                    <i class="fa fa-ballot-check"></i> {{ trans('menus.backend.course.content.module-menu-item.add_qse') }}
                </a>
                <a href="#"
                   class="dropdown-item video-upload"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $contentTypeVideo }}"
                   data-parent-id="{{ $module->id }}">
                    <i class="fa fa-file-video"></i> {{ trans('menus.backend.course.content.module-menu-item.add_video') }}
                </a>
                <a href="#"
                   class="dropdown-item module-rename"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $contentTypePage }}"
                   data-parent-id="{{ $module->id }}"
                   data-menu-title="{{ $module->menu_title }}"
                   data-id="{{$module->id}}">
                    <i class="fa fa-edit"></i> {{ trans('menus.backend.course.content.module-menu-item.rename_module') }}
                </a>
                @if($module->retired_date)
                    <a href="/course/content/{{$module->id}}/activatemodule" class="dropdown-item">
                        <i class="fa fa-play"
                           aria-hidden="true"></i> {{ trans('menus.backend.course.content.module-menu-item.activate') }}
                    </a>
                @else
                    <a href="/course/content/{{$module->id}}/retiremodule" class="dropdown-item">
                        <i class="fa fa-pause"
                           aria-hidden="true"></i> {{ trans('menus.backend.course.content.module-menu-item.retire') }}
                    </a>
                @endif
                <div class="dropdown-divider"></div>
                <a href="#"
                   class="dropdown-item module-delete {{ $module->contentItems->isnotempty() ? 'disabled' : 'text-danger' }}"
                   data-viewer-type-id="{{ $viewerTypeId }}"
                   data-content-type-id="{{ $module->content_type_id }}"
                   data-parent-id="{{ $module->id }}"
                   data-menu-title="{{ $module->menu_title }}"
                   data-content-items="{{ count($module->contentItems) }}"
                   data-id="{{$module->id}}">
                    <i class="fa fa-trash"
                       aria-hidden="true"></i> {{ trans('menus.backend.course.content.module-menu-item.delete_module') }}
                </a>
            </div>
        </div>
    </div>

    {{--    Items--}}
    <div id="collapseItem{{$module->id}}"
         data-module-id="{{$module->id}}"
         class="card-body p-0 panel-collapse collapse">
        <ul class="list-group list-group-flush sortable-items w-100 px-5" data-module-id="{{$module->id}}"
            data-viewer-type-id="{{ $module->viewer_type_id }}">
            @foreach($module->contentItems()->whereRaw('menu_id = id')->get() as $item)
                <li id="item-{{ $item->id }}"
                    class="list-group-item module-subitem px @if($item->retired_date) retired @endif"
                    data-id="{{ $item->id }}"
                    data-parent-id="{{ $module->id }}"
                    data-viewer-type-id="{{ $item->viewer_type_id }}"
                    data-content-type-id="{{ $item->content_type_id }}"
                >
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="text-indent: {{ $item->indent_level * 25 }}px;">
                            <i class="fa fa-grip-vertical sort-handle mr-3" aria-hidden="true"></i>
                            @if($item->retired_date)
                                @if( $item->content_type_id == 2)
                                    <i class="fa fa-file mr-2" aria-hidden="true"></i>
                                @elseif($item->content_type_id == 3)
                                    <i class="fa fa-video mr-2" aria-hidden="true"></i>
                                @elseif($item->content_type_id == 4)
                                    <i class="fa fa-file-alt mr-2" aria-hidden="true"></i>
                                @elseif($item->content_type_id == 5)
                                    <i class="fa fa-file-download mr-2" aria-hidden="true"></i>
                                @elseif($item->content_type_id == 6)
                                    <i class="fa fa-presentation mr-2" aria-hidden="true"></i>
                                @elseif($item->content_type_id == 7)
                                    <i class="fa fa-ballot-check mr-2" aria-hidden="true"></i>
                                @endif
                                {{ $item->menu_title }} (Retired)
                            @else
                                @if( $item->content_type_id == 2)
                                    <a href="/course/content/page/{{ $item->id }}/edit"
                                       class="text-primary text-decoration-none" style="text-indent: 0">
                                        <i class="fa fa-file mr-2" aria-hidden="true"></i>
                                        {{ $item->menu_title }}
                                    </a>
                                @elseif($item->content_type_id == 3)
                                    <a href="/course/content/video/{{ $item->id }}/edit"
                                       class="text-primary text-decoration-none" style="text-indent: 0">
                                        <i class="fa fa-video mr-2" aria-hidden="true"></i>
                                        {{ $item->menu_title }}
                                    </a>
                                @elseif($item->content_type_id == 4)
                                    <a href="/course/content/office/{{ $item->id }}/edit"
                                       class="text-primary text-decoration-none" style="text-indent: 0">
                                        <i class="fa fa-file-alt mr-2" aria-hidden="true"></i>
                                        {{ $item->menu_title }}
                                    </a>
                                @elseif($item->content_type_id == 5)
                                    <a href="/course/content/office/{{ $item->id }}/edit"
                                       class="text-primary text-decoration-none" style="text-indent: 0">
                                        <i class="fa fa-file-download mr-2" aria-hidden="true"></i>
                                        {{ $item->menu_title }}
                                    </a>
                                @elseif($item->content_type_id == 6)
                                    <a href="/course/content/presenter/{{ $item->id }}/edit"
                                       class="text-primary text-decoration-none" style="text-indent: 0">
                                        <i class="fa fa-presentation mr-2" aria-hidden="true"></i>
                                        {{ $item->menu_title }}
                                    </a>
                                @elseif($item->content_type_id == 7)
                                    <a href="/course/content/qse/{{ $item->id }}/edit"
                                       class="text-primary text-decoration-none" style="text-indent: 0">
                                        <i class="fa fa-ballot-check mr-2" aria-hidden="true"></i>
                                        {{ $item->menu_title }}
                                    </a>
                                @endif
                            @endif
                        </div>
                        {{-- Menu--}}
                        <div style="text-indent: 0px;" class="dropdown">
                            @if($item->retired_date)
                                <button class="btn btn-link btn-sm">
                                    <i class="fa fa-eye-slash"></i>
                                </button>
                            @endif

                            @switch($item->publishedStatus)
                                @case(1)
                                @if( $item->content_type_id === 2)
                                    <button class="btn btn-link btn-sm">
                                                <span class="simptip-position-top simptip-smooth"
                                                      data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                                                    <a href="/course/content/{{ $item->id }}/page/publish">
                                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                                    </a>
                                                </span>
                                    </button>
                                @elseif($item->content_type_id === 7)
                                    <button class="btn btn-link btn-sm" {{ $item->qseCanBePublish() == 0 ? 'disabled' : '' }}>
                                        <span class="simptip-position-top simptip-smooth"
                                              data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                                            <a href="/course/content/{{ $item->id }}/qse/publish">
                                                <i class="fa fa-ban" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </button>
                                @else
                                    <button class="btn btn-link btn-sm">
                                                <span class="simptip-position-top simptip-smooth"
                                                      data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                                                    <a href="/course/content/{{ $item->id }}/video/publish">
                                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                                    </a>
                                                </span>
                                    </button>
                                @endif
                                @break
                                @case(2)
                                <button class="btn btn-link btn-sm">
                                            <span class="simptip-position-top simptip-smooth"
                                                  data-tooltip="{{ trans('labels.course_curriculum.published') }}">
                                                <i class="fa fa-check" style="color: green;" aria-hidden="true"></i>
                                            </span>
                                </button>
                                @break
                                @case(3)
                                @if( $item->content_type_id === 2)
                                    <button class="btn btn-link btn-sm">
                                                <span class="simptip-position-top simptip-smooth"
                                                      data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                                                    <a href="/course/content/{{ $item->id }}/page/publish">
                                                        <i class="fa fa-exclamation-triangle" style="color: orange;"
                                                           aria-hidden="true"></i>
                                                    </a>
                                                </span>
                                    </button>
                                @elseif($item->content_type_id === 7)
                                    <button class="btn btn-link btn-sm">
                                        <span class="simptip-position-top simptip-smooth"
                                              data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                                            <a href="/course/content/{{ $item->id }}/qse/publish">
                                                <i class="fa fa-exclamation-triangle" style="color: orange;"
                                                   aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </button>
                                @else
                                    <button class="btn btn-link btn-sm">
                                                <span class="simptip-position-top simptip-smooth"
                                                      data-tooltip="{{ trans('labels.course_curriculum.click-to-publish') }}">
                                                    <a href="/course/content/{{ $item->id }}/video/publish">
                                                        <i class="fa fa-exclamation-triangle" style="color: orange;"
                                                           aria-hidden="true"></i>
                                                    </a>
                                                </span>
                                    </button>
                                @endif
                                @break
                            @endswitch

                            <button class="btn btn-outline-primary btn-sm py-0 bop"
                                    type="button" id="dropdownMenu"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="true"
                            >
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right"
                                 aria-labelledby="dropdownMenu">
                                <a class="dropdown-item page-rename"
                                   data-id="{{ $item->id }}"
                                   data-parent-id="{{ $module->id }}"
                                   data-viewer-type-id="{{ $item->viewer_type_id }}"
                                   data-content-type-id="{{ $item->content_type_id }}"
                                   data-menu-title="{{ $item->menu_title }}"
                                   href="/course/content/page/{{ $item->id }}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i> Rename
                                </a>

                                @if(!$item->retired_date && $item->content_type_id == 2)
                                    <a href="/course/content/page/{{ $item->id }}/edit" class="dropdown-item">
                                        <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                                    </a>
                                @endif

                                @if($item->content_type_id != 7)
                                    <a href="/course/content/{{ $item->id }}/duplicate" class="dropdown-item">
                                        <i class="fa fa-clone" aria-hidden="true"></i>
                                        Duplicate
                                    </a>
                                @endif

                                @if(!$module->retired_date)
                                    @if($item->retired_date)
                                        <a href="/course/content/{{$item->id}}/activate" class="dropdown-item">
                                            <i class="fa fa-play" aria-hidden="true"></i> Activate
                                        </a>
                                    @else
                                        <a href="/course/content/{{$item->id}}/retire" class="dropdown-item">
                                            <i class="fa fa-pause" aria-hidden="true"></i> Retire
                                        </a>
                                    @endif
                                @endif
                                @if($item->indent_level < 5)
                                    <a href="/course/content/{{$item->id}}/indent" class="dropdown-item">
                                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                        Increase Indent
                                    </a>
                                @endif
                                @if($item->indent_level > 0)
                                    <a href="/course/content/{{$item->id}}/outdent" class="dropdown-item">
                                        <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                        Decrease Indent
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                @if($item->qse && $item->published_date != null)
                                @elseif( $item->content_type_id != 2)
                                    <a onclick="deleteFile(this);"
                                       href="#" data-id="{{$item->id}}"
                                       data-title="{{addslashes($item->menu_title)}}" class="dropdown-item text-danger">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                    </a>
                                @elseif($item->content_type_id === 2)
                                    <a onclick="deletePage(this);" href="#" data-id="{{$item->id}}"
                                       data-title="{{addslashes($item->menu_title)}}" class="dropdown-item text-danger">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
