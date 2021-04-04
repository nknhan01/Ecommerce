<ul {!! $options !!}>
    @foreach ($menu_nodes as $key => $row)
        <li class="@if ($row->has_child) dropdown @endif @if ($row->css_class) {{ $row->css_class }} @endif @if ($row->active) active @endif">
            <a class="@if ($row->has_child) dropdown-toggle nav-link @else nav-link nav_item @endif" href="{{ url($row->url) }}" @if ($row->target !== '_self') target="{{ $row->target }}" @endif @if ($row->has_child) data-toggle="dropdown" @endif>
                @if ($row->icon_font) <i class="{{ trim($row->icon_font) }}"></i> @endif {{ $row->title }}
            </a>
            @if ($row->has_child)
                <div class="dropdown-menu">
                    {!! Menu::generateMenu([
                        'menu'       => $menu,
                        'menu_nodes' => $row->child,
                        'view'       => 'menu',
                    ]) !!}
                </div>
            @endif
        </li>
    @endforeach
</ul>
