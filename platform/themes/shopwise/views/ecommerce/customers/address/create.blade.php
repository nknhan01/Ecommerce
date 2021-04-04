@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('My Addresses')) @endphp
    <div class="card">
        <div class="card-header">
            <h3>{{ __('Add a new address') }}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'customer.address.create']) !!}
            <div class="form-group">
                <label>{{ __('Full Name') }}:</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
            </div>
            {!! Form::error('name', $errors) !!}

            <div class="form-group">
                <label>{{ __('Email') }}:</label>
                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
            </div>
            {!! Form::error('email', $errors) !!}

            <div class="form-group">
                <label>{{ __('Phone') }}:</label>
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">

            </div>
            {!! Form::error('phone', $errors) !!}

            @if (count(EcommerceHelper::getAvailableCountries()) > 1)
                <div class="form-group @if ($errors->has('country')) has-error @endif">
                    <label for="country">{{ __('Country') }}:</label>
                    <select name="country" class="form-control" id="country">
                        @foreach(['' => __('Select country...')] + EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                            <option value="{{ $countryCode }}" @if (old('country') == $countryCode) selected @endif>{{ $countryName }}</option>
                        @endforeach
                    </select>
                </div>
                {!! Form::error('country', $errors) !!}
            @else
                <input type="hidden" name="country" value="{{ Arr::first(array_keys(EcommerceHelper::getAvailableCountries())) }}">
            @endif

            <div class="form-group @if ($errors->has('state')) has-error @endif">
                <label>{{ __('State') }}:</label>
                <input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}">

            </div>
            {!! Form::error('state', $errors) !!}

            <div class="form-group @if ($errors->has('city')) has-error @endif">
                <label>{{ __('City') }}:</label>
                <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}">

            </div>
            {!! Form::error('city', $errors) !!}

            <div class="form-group">
                <label>{{ __('Address') }}:</label>
                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}">

            </div>
            {!! Form::error('address', $errors) !!}

            @if (EcommerceHelper::isZipCodeEnabled())
                <div class="form-group">
                    <label>{{ __('Zip code') }}:</label>
                    <input id="zip_code" type="text" class="form-control" name="zip_code" value="{{ old('zip_code') }}">
                    {!! Form::error('zip_code', $errors) !!}
                </div>
            @endif

            <div class="form-group">
                <label for="is_default">
                    <input type="checkbox" name="is_default" value="1" id="is_default">
                    {{ __('Use this address as default.') }}

                </label>
            </div>
            {!! Form::error('is_default', $errors) !!}

            <div class="form-group text-center">
                <button class="btn btn-fill-out btn-sm" type="submit">{{ __('Add a new address') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
