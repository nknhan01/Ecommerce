@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')

    @php Theme::set('pageName', __('Overview')) @endphp
    <div class="card">
        <div class="card-header">
            <h3>{{ __('Account information') }}</h3>
        </div>
        <div class="card-body">
            <div class="form-group"><i class="fa fa-user"></i> {{ __('Name') }}: <strong>{{ auth('customer')->user()->name }}</strong></div>
            <div class="form-group"><i class="fa fa-calendar"></i> {{ __('Date of birth') }}: <strong>{{ auth('customer')->user()->dob ? auth('customer')->user()->dob : __('N/A') }}</strong></div>
            <div class="form-group"><i class="fa fa-envelope"></i> {{ __('Email') }}: <strong>{{ auth('customer')->user()->email }}</strong></div>
            <div class="form-group"><i class="fa fa-phone"></i> {{ __('Phone') }}: <strong>{{ auth('customer')->user()->phone ? auth('customer')->user()->phone : __('N/A') }}</strong></div>
        </div>
    </div>

@endsection
