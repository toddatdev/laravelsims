@extends ('backend.layouts.app')

@section ('title', trans('menus.backend.resource.title'))

@section('after-styles')
@stop

@section('page-header')
    <h4>{{ trans('menus.backend.resourceCategory.title') }}</h4>
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resourceCategory.index') }}</h3>
                        <div class="float-right">
                            @include('resources.partial-header-buttons-category')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-columns">
                            @foreach ($categories as $category)

                                <li class="card">
                                    <div class="card-header bg-light">
                                        <h4 class="card-title">
                                            <label for="category" class="" data-toggle="tooltip" title="{{ $category->description }}">{{ $category->abbrv }}</label>
                                        </h4>

                                        <a href="/resources/resourceCategory/edit/{{$category->id}}" class="btn-xs ml-2">
                                            <i class="fa fa-pencil-alt fa-lg text-primary" data-toggle="tooltip" data-placement="top"
                                               title="{{trans('buttons.backend.resources.editCategory')}}"></i>
                                        </a>

                                        {{--Can only delete if subcategory not assigned to resource--}}
                                        @if ($category->canDelete())
                                            {!! Form::open(['method'=>'DELETE', 'style'=>'display:inline-block', 'class'=>'btn-xs', 'route'=>['ResourceCategory.destroy',$category->id]]) !!}
                                            <button style="background-color: transparent; border:none" id="group" data-toggle="tooltip" data-placement="top"
                                                    title="{{trans('buttons.backend.resources.deleteCategory')}}" type="submit"
                                                    class="fa fa-trash fa-lg no-border deleteIcon"
                                                    onclick="return confirm('{{trans('alerts.backend.resourcecategory.delete', ['categoryAbbrv' => $category->abbrv])}}');"></button>
                                            {!! Form::close() !!}
                                        @endif

                                        <a href="/resources/resourceSubCategory/create/{{$category->id}}" class="btn-xs">
                                            <i class="fa fa-plus fa-lg text-success" data-toggle="tooltip" data-placement="top"
                                               title="{{trans('buttons.backend.resources.addSubCategory')}}"></i>
                                        </a>

                                    </div>

                                    <ul class="list-group list-group-flush">

                                        <?php $subCategories = \App\Models\Resource\ResourceSubCategory::orderBy('abbrv')
                                            ->where('resource_category_id', $category->id)
                                            ->get(); ?>
                                        @foreach ($subCategories as $subCategory)
                                            <li class="list-group-item">
                                                <span title="{{ $subCategory->description }}">{{ $subCategory->abbrv }}</span>
                                                <a href="/resources/resourceSubCategory/edit/{{$subCategory->id}}" class="btn-sm">
                                                    <i class="fa fa-pencil-alt fa-sm iconEdit" data-toggle="tooltip" data-placement="top"
                                                       title="{{trans('buttons.backend.resources.editSubCategory')}}"></i>
                                                </a>

                                                {{--Can only delete if subcategory not assigned to resource--}}
                                                @if ($subCategory->canDelete())
                                                    {!! Form::open(['method'=>'DELETE', 'style'=>'display:inline-block', 'route'=>['ResourceSubCategory.destroy',$subCategory->id]]) !!}
                                                    <button id="subcategory" style="border:none" data-toggle="tooltip" data-placement="top" title="{{trans('buttons.backend.resources.deleteSubCategory')}}"
                                                            type="submit" class="fa fa-trash fa-sm no-border deleteIcon"
                                                            onclick="return confirm('{{trans('alerts.backend.resourcesubcategory.delete', ['subcategoryAbbrv' => $subCategory->abbrv])}}');"></button>
                                                    {!! Form::close() !!}
                                                @else
                                                    {{--mitcks - can't decide if we should display disabled or just hide, hiding for now--}}
                                                    {{--<button id="subcategory" data-toggle="tooltip" data-placement="top"--}}
                                                            {{--title="{{trans('buttons.backend.resources.disabledDeleteSubCategory')}}"--}}
                                                            {{--type="submit" class="fa fa-trash fa-xs no-border disabledIcon"></button>--}}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                         </div>
                    </div>
                </div>
            </div>
    </section>

@stop

