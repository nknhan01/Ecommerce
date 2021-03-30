@if (count($referrers) > 0)
    <div class="scroller">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('core/base::tables.url') }}</th>
                    <th>{{ trans('core/base::tables.views') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($referrers as $referrer)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="text-left">{{ $referrer['url'] }}</td>
                        <td>{{ $referrer['pageViews'] }} ({{ ucfirst(trans('plugins/analytics::analytics.views')) }})</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @include('core/dashboard::partials.no-data')
@endif