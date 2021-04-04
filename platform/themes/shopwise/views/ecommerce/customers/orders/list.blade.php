@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('Orders')) @endphp

    <div class="card">
        <div class="card-header">
            <h3>{{ __('Orders') }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('ID number') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($orders) > 0)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ get_order_code($order->id) }}</td>
                                    <td>{{ $order->created_at->format('Y/m/d h:m') }}</td>
                                    <td>{{ format_price($order->amount) }}</td>
                                    <td>{!! $order->status->toHtml() !!}</td>
                                    <td>
                                        <a class="btn btn-fill-out btn-sm" href="{{ route('customer.orders.view', $order->id) }}">{{ __('View') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No orders!') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-3 justify-content-center pagination_style1">
                {!! $orders->links() !!}
            </div>
        </div>
    </div>
@endsection
