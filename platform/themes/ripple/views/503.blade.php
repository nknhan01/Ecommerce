@extends('theme.' . setting('theme') . '::views.error-master')

@section('code', '503')
@section('title', __('Service Unavailable'))
@section('message')
    <h4>{{ __($exception->getMessage() ?: 'Sorry, we are doing some maintenance. Please check back soon.') }}</h4>
@endsection
