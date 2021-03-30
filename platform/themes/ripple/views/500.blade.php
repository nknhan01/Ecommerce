@extends('theme.' . setting('theme') . '::views.error-master')

@section('code', '500')
@section('title', __('Page could not be loaded'))

@section('message')
    <h4>{{ __('This may have occurred because of several reasons') }}</h4>
    <ul>
        <li>{{ __('The page you requested does not exist.') }}</li>
        <li>{{ __('The link you clicked is no longer.') }}</li>
        <li>{{ __('The page may have moved to a new location.') }}</li>
        <li>{{ __('An error may have occurred.') }}</li>
        <li>{{ __('You are not authorized to view the requested resource.') }}</li>
    </ul>
@endsection
