<ul {!! $options !!}>
    @foreach ($items as $key => $row)
        @php $id = 'menu-id-' . strtolower(Str::slug(str_replace('\\', ' ', $type))) . '-' . $row->id; @endphp
        <li>
            <label for="{{ $id }}" data-title="{{ $row->name }}" data-reference-id="{{ $row->id }}" data-reference-type="{{ $type }}">
                {!! Form::checkbox('menu_id', $row->id, null, compact('id')) !!}
                {{ $row->name }}
            </label>

            @if ($row->children)
                {!!
                    Menu::generateSelect([
                        'model' => $model,
                        'type'  => $type,
                        'items' => $row->children
                    ])
                !!}
            @endif
        </li>
    @endforeach
</ul>
