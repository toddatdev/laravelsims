<ul class="dropdown-menu" role="menu">
        @foreach (array_keys(config('locale.languages')) as $lang)
                @if ($lang != App::getLocale())
                        <li>{{ link_to('lang/'.$lang, trans('menus.language-picker.langs.'.$lang)) }}</li>
                @endif
        @endforeach
</ul>

<div class="dropdown-menu" aria-labelledby="navbarDropdown">
{{--    <a class="dropdown-item" href="#">FAQ</a>--}}
{{--    <a class="dropdown-item" href="#">Support</a>--}}
{{--    <div class="dropdown-divider"></div>--}}
{{--    <a class="dropdown-item" href="#">Contact</a>--}}
    @foreach (array_keys(config('locale.languages')) as $lang)
        @if ($lang != App::getLocale())
{{--            <a class="dropdown-item" href="lang/{{$lang}}">{{trans('menus.language-picker.langs.'.$lang)}}</a>--}}
{{--            <li>{{ link_to('lang/'.$lang, trans('menus.language-picker.langs.'.$lang)) }}</li>--}}
            {{ link_to('lang/'.$lang, trans('menus.language-picker.langs.'.$lang)) }}
        @endif
    @endforeach
</div>