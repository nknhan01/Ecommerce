<ul {!! $options !!}>
    @foreach ($items as $key => $row)
        <li>
            <label for="menu_id_{{ strtolower(Str::slug($type)) }}_{{ $row->id }}" data-title="{{ $row->name }}" data-reference-id="{{ $row->id }}"
                   data-reference-type="{{ $type }}">
                {!! Form::checkbox('menu_id', $row->id, null, ['id' => 'menu_id_' . $type . '_' . $row->id]) !!}
                {{ $row->name }}
            </label>

            @if ($row->children)
                {!!
                    Menu::generateSelect([
                        'model'     => $model,
                        'type'      => $type,
                        'parent_id' => $row->id
                    ])
                !!}
            @endif
        </li>
    @endforeach
</ul>
