@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('Change password')) @endphp
    <div class="card">
        <div class="card-header">
            <h3>{{ __('Change password') }}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'customer.post.change-password', 'method' => 'post']) !!}
            <div class="form-group @if ($errors->has('old_password')) has-error @endif">
                <label for="old_password">{{ __('Current password') }}:</label>
                <input type="password" class="form-control" name="old_password" id="old_password"
                       placeholder="{{ __('Current Password') }}">
                {!! Form::error('old_password', $errors) !!}
            </div>
            <div class="form-group @if ($errors->has('password')) has-error @endif">
                <label for="password">{{ __('New password') }}:</label>
                <input type="password" class="form-control" name="password" id="password"
                       placeholder="{{ __('New Password') }}">
                {!! Form::error('password', $errors) !!}
            </div>
            <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                <label for="password_confirmation">{{ __('Password confirmation') }}:</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                       placeholder="{{ __('Password Confirmation') }}">
                {!! Form::error('password_confirmation', $errors) !!}
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-fill-out btn-sm">{{ __('Change password') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
