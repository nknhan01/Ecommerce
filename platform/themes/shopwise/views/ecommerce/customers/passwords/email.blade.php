@php Theme::set('pageName', __('Reset Password')) @endphp

<!-- START LOGIN SECTION -->
<div class="login_register_wrap section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-10">
                <div class="login_wrap">
                    <div class="padding_eight_all bg-white">
                        <div class="heading_s1">
                            <h3>{{ __('Reset Password') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('customer.password.request') }}">
                            @csrf
                            <div class="form-group">
                                <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email') }}" placeholder="{{ __('Your Email') }}">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-fill-out btn-block">{{ __('Send Password Reset Link') }}</button>
                            </div>

                            @if (session('status'))
                                <div class="text-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END LOGIN SECTION -->
