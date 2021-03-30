@foreach($statuses as $key => $status)
    @if ($key == $selected)
        <span class="{{ Arr::get($status, 'class', 'label-info') }} status-label">
            {{ Arr::get($status, 'text') }}
        </span>
    @endif
@endforeach