@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'Courses', 'subtitle' => 'Browse and enroll in trading courses'])

    <livewire:user.system-courses />
@endsection
