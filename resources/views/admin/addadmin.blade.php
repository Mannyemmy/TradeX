@extends('layouts.admin-dash')
@section('title', 'Add New Manager')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Add New Manager" subtitle="Create a new admin or agent account.">
        <x-slot name="actions">
            <a href="{{ route('madmin') }}"
                class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Managers
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Validation Errors --}}
    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    {{-- Form --}}
    <div class="mt-6 max-w-2xl mx-auto">
        <x-admin.card>
            <form method="POST" action="{{ url('admin/dashboard/saveadmin') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form-group label="First Name" for="fname" :required="true" :error="$errors->first('fname')">
                        <input id="fname" type="text" name="fname" value="{{ old('fname') }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Last Name" for="l_name" :required="true" :error="$errors->first('l_name')">
                        <input id="l_name" type="text" name="l_name" value="{{ old('l_name') }}" required
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

                    <x-admin.form-group label="Type" for="type" :required="true">
                        <select name="type"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option>Super Admin</option>
                            <option>Admin</option>
                            <option>Conversion Agent</option>
                        </select>
                    </x-admin.form-group>

                    <div></div>

                    <x-admin.form-group label="Password" for="password" :required="true" :error="$errors->first('password')">
                        <input id="password" type="password" name="password" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Confirm Password" for="password_confirmation" :required="true" :error="$errors->first('password_confirmation')">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <div class="md:col-span-2">
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Save Manager
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
