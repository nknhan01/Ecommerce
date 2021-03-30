<nav id="admin_bar">
    <div class="admin-bar-container">
        <div class="admin-bar-logo">
            <a href="{{ route('dashboard.index') }}" title="Go to dashboard">
                <img src="{{ setting('admin_logo') ? get_image_url(setting('admin_logo')) : url(config('core.base.general.logo')) }}" alt="logo"/>
            </a>
        </div>
        <ul class="admin-navbar-nav">
            @foreach (admin_bar()->getGroups() as $slug => $group)
                @if (Arr::get($group, 'items'))
                    <li class="admin-bar-dropdown">
                        <a href="{{ Arr::get($group, 'link') }}" class="dropdown-toggle">
                            {{ Arr::get($group, 'title') }}
                        </a>
                        <ul class="admin-bar-dropdown-menu">
                            <li><a href="{{ route('dashboard.index') }}" title="Go to dashboard">{{ __('Dashboard') }}</a></li>
                            @foreach (Arr::get($group, 'items', []) as $title => $link)
                                <li>
                                    <a href="{{ $link ?? '' }}">
                                        {{ $title ?? '' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
            @foreach (admin_bar()->getLinksNoGroup() as $item)
                <li>
                    <a href="{{ Arr::get($item, 'link') }}">{{ Arr::get($item, 'title') }}</a>
                </li>
            @endforeach
        </ul>
        <ul class="admin-navbar-nav admin-navbar-nav-right">
            <li class="admin-bar-dropdown">
                <a href="{{ route('user.profile.view', ['id' => Auth::user()->getKey()]) }}" class="dropdown-toggle">
                    {{ Auth::user()->getFullName() }}
                </a>
                <ul class="admin-bar-dropdown-menu">
                    <li><a href="{{ route('user.profile.view', Auth::user()->getKey()) }}"><i class="icon-user"></i> {{ trans('core/base::layouts.profile') }}</a></li>
                    <li><a href="{{ route('access.logout') }}">{{ trans('core/base::layouts.logout') }}</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<script type="text/javascript">
    document.getElementsByTagName('body')[0].classList.add('show-admin-bar');
</script>
