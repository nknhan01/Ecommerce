@if (session()->has('success_msg'))
    <div class="alert alert-success">
        <span>{{ session('success_msg') }}</span>
    </div>
@endif
@if (session()->has('error_msg'))
    <div class="alert alert-danger">
        <span>{{ session('error_msg') }}</span>
    </div>
@endif
@if (isset($error_msg) && !empty($error_msg))
    <div class="alert alert-danger">
        <span>{{ $error_msg }}</span>
    </div>
@endif
@if (isset($errors) && !empty($errors) && count($errors->all()) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li><span>{{ $error }}</span></li>
            @endforeach
        </ul>
    </div>
@endif

@php
    session()->forget('success_msg');
    session()->forget('error_msg');
    $error_msg = null;
    $errors = new \Illuminate\Support\ViewErrorBag();
@endphp