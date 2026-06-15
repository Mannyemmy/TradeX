@extends('layouts.admin-dash')
@section('title', 'Add New User')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Add New User" subtitle="Add a new user to {{ $settings->site_name }} community." />

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

    {{-- Form --}}
    <div class="mt-6 max-w-2xl mx-auto">
        <x-admin.card>
            <form method="POST" action="{{ url('admin/dashboard/saveuser') }}">
                @csrf
                <div class="space-y-4">
                    <x-admin.form-group label="Username" for="username" :required="true" :error="$errors->first('username')">
                        <input type="text" name="username" placeholder="Enter Unique Username" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Full Name" for="name" :required="true" :error="$errors->first('name')">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="E-Mail Address" for="email" :required="true" :error="$errors->first('email')">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Phone Number" for="phone" :required="true" :error="$errors->first('phone')">
                        <input id="phone" type="number" name="phone" value="{{ old('phone') }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Password" for="password" :required="true" :error="$errors->first('password')">
                        <input id="password" type="password" name="password" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Confirm Password" for="password_confirmation" :required="true" :error="$errors->first('password_confirmation')">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <div>
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            Save User
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
