<ul {!! clean($options) !!}>
    @foreach ($menu_nodes as $key => $row)
    <li class="menu-item @if ($row->has_child) menu-item-has-children dropdown @endif {{ $row->css_class }} @if ($row->active) active @endif">
        <a href="{{ $row->url }}" target="{{ $row->target }}">
            @if ($row->icon_font)<i class='{{ trim($row->icon_font) }}'></i> @endif{{ $row->title }}
        </a>
        @if ($row->has_child)
            {!!
                Menu::generateMenu([
                    'menu'       => $menu,
                    'view'       => 'main-menu',
                    'options'    => ['class' => 'dropdown-menu'],
                    'menu_nodes' => $row->child,
                ])
            !!}
        @endif
    </li>
    @endforeach
</ul>
