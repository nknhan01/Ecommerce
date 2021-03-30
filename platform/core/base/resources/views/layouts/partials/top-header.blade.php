<div class="page-header navbar navbar-static-top">
    <div class="page-header-inner col-12">

            <div class="page-logo">
                <a href="{{ route('dashboard.index') }}">
                    <img src="{{ setting('admin_logo') ? get_image_url(setting('admin_logo')) : url(config('core.base.general.logo')) }}" alt="logo" class="logo-default" />
                </a>
                @auth
                    <div class="menu-toggler sidebar-toggler">
                        <span></span>
                    </div>
                @endauth
            </div>

            @auth
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span></span>
                </a>
            @endauth

            <div class="top-menu">
                <ul class="nav navbar-nav float-right">
                    @auth
                        @if (config('core.base.general.admin_dir') != '')
                            <li class="dropdown">
                                <a class="dropdown-toggle dropdown-header-name" style="padding-right: 10px" href="{{ url('/') }}" target="_blank"><i class="fa fa-globe"></i> <span class="d-none d-sm-inline">{{ trans('core/base::layouts.view_website') }}</span> </a>
                            </li>
                        @endif
                        @if (Auth::check())
                            {!! apply_filters(BASE_FILTER_TOP_HEADER_LAYOUT, null) !!}
                        @endif

                        @if (isset($themes) && setting('enable_change_admin_theme') != false)
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>{{ trans('core/base::layouts.theme') }}</span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right icons-right">

                                    @foreach ($themes as $name => $file)
                                        @if ($activeTheme === $name)
                                            <li class="active"><a href="{{ route('admin.theme', [$name]) }}">{{ Str::studly($name) }}</a></li>
                                        @else
                                            <li><a href="{{ route('admin.theme', [$name]) }}">{{ Str::studly($name) }}</a></li>
                                        @endif
                                    @endforeach

                                </ul>
                            </li>
                        @endif

                        @if (setting('enable_multi_language_in_admin') != false && count($locales) > 1)
                            <li class="language dropdown">
                                <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if (array_key_exists(app()->getLocale(), $locales))
                                        {!! language_flag($locales[app()->getLocale()]['flag'], $locales[app()->getLocale()]['name']) !!}
                                        <span class="d-none d-sm-inline">{{ $locales[app()->getLocale()]['name'] }}</span>
                                    @endif
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right icons-right">
                                    @foreach ($locales as $key => $value)
                                        @if (app()->getLocale() == $key)
                                            <li class="active">
                                                <a href="{{ route('settings.language', $key) }}">
                                                    {!! language_flag($value['flag'], $value['name']) !!} <span>{{ $value['name'] }}</span>
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ route('settings.language', $key) }}">
                                                    {!! language_flag($value['flag'], $value['name']) !!} <span>{{ $value['name'] }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        <li class="dropdown dropdown-user">
                            <a href="javascript:void(0)" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img alt="{{ Auth::user()->getFullName() }}" class="rounded-circle" src="{{ Auth::user()->avatar_url }}" />
                                <span class="username username-hide-on-mobile"> {{ Auth::user()->getFullName() }} </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('user.profile.view', Auth::user()->getKey()) }}"><i class="icon-user"></i> {{ trans('core/base::layouts.profile') }}</a></li>
                                <li><a href="{{ route('access.logout') }}" class="btn-logout"><i class="icon-key"></i> {{ trans('core/base::layouts.logout') }}</a></li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
</div>
