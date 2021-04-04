@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('Account details')) @endphp
    <div class="card">
        <div class="card-header">
            <h3>{{ __('Account information') }}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'customer.edit-account']) !!}
                <div class="form-group">
                    <label for="name">{{ __('Full Name') }}:</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ auth('customer')->user()->name }}">
                </div>
                {!! Form::error('name', $errors) !!}

                <div class="form-group @if ($errors->has('dob')) has-error @endif">
                    <label for="date_of_birth">{{ __('Date of birth') }}:</label>
                    <input id="date_of_birth" type="text" class="form-control" name="dob" value="{{ auth('customer')->user()->dob }}">
                </div>
                {!! Form::error('dob', $errors) !!}
                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <label for="email">{{ __('Email') }}:</label>
                    <input id="email" type="text" class="form-control" disabled="disabled" value="{{ auth('customer')->user()->email }}" name="email">
                </div>
                {!! Form::error('email', $errors) !!}

                <div class="form-group @if ($errors->has('phone')) has-error @endif">
                    <label for="phone">{{ __('Phone') }}</label>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="{{ __('Phone') }}" value="{{ auth('customer')->user()->phone }}">

                </div>
                {!! Form::error('phone', $errors) !!}

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-fill-out btn-sm">{{ __('Update') }}</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
