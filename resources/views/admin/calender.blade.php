@extends('layouts.admin-dash')
@section('title', 'Calendar / To-Do List')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Calendar" subtitle="Create your to-do list and manage your schedule." />

    {{-- Flash Messages --}}
    @if (session('message'))
        <x-admin.alert type="info" :dismissible="true" class="mt-4">
            {{ session('message') }}
        </x-admin.alert>
    @endif

    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Calendar Embed --}}
    <div class="mt-6">
        <x-admin.card>
            <div>
                <script src="//localendar.com/public/Victory33404?current_only=Y&include=Y&dynamic=Y"></script>
            </div>
        </x-admin.card>
    </div>
@endsection
