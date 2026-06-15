@extends('layouts.admin-dash')
@section('title', 'Account Profile')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Account Profile" subtitle="View and update your profile information.">
        <x-slot name="actions">
            <a href="{{ url('admin/dashboard/adminchangepassword') }}"
                class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                Change Password
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

    {{-- Profile Form --}}
    <div class="mt-6 max-w-xl mx-auto">
        <x-admin.card>
            <form method="post" action="{{ route('upadprofile') }}">
                @csrf
                <div class="space-y-4">
                    <x-admin.form-group label="First Name" for="name" :required="true" :error="$errors->first('name')">
                        <input id="name" type="text" name="name" value="{{ Auth('admin')->User()->firstName }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Last Name" for="lname" :required="true" :error="$errors->first('lname')">
                        <input id="lname" type="text" name="lname" value="{{ Auth('admin')->User()->lastName }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Phone Number" for="phone" :error="$errors->first('phone')">
                        <input id="phone" type="text" name="phone" value="{{ Auth('admin')->User()->phone }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Two Factor Authentication" for="token">
                        <select id="token" name="token"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="{{ Auth('admin')->User()->enable_2fa }}" selected>{{ Auth('admin')->User()->enable_2fa }}</option>
                            <option value="enabled">Enable</option>
                            <option value="disabled">Disable</option>
                        </select>
                    </x-admin.form-group>

                    <div class="pt-2">
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Update Profile
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
