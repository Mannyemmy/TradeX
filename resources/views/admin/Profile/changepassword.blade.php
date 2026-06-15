@extends('layouts.admin-dash')
@section('title', 'Change Password')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Change Your Password" subtitle="Update your account password for security.">
        <x-slot name="actions">
            <a href="{{ route('adminprofile') }}"
                class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Profile
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Change Password Form --}}
    <div class="mt-6 max-w-xl mx-auto">
        <x-admin.card>
            <form method="post" action="{{ route('adminupdatepass') }}">
                @csrf
                <div class="space-y-4">
                    <x-admin.form-group label="Old Password" for="old_password" :required="true" :error="$errors->first('old_password')">
                        <input id="old_password" type="password" name="old_password" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="New Password" for="password" :required="true" :error="$errors->first('password')">
                        <input id="password" type="password" name="password" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Confirm Password" for="password_confirmation" :required="true">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <input type="hidden" name="id" value="{{ Auth('admin')->User()->id }}">

                    <div class="pt-2">
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
