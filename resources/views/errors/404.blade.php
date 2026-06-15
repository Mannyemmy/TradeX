@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('The page you\'re looking for doesn\'t exist or has been moved. Check the URL or head back home.'))

@section('icon')
{{-- Heroicon: magnifying-glass --}}
<svg class="w-10 h-10 text-warning" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
</svg>
@endsection
