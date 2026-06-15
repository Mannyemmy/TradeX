@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Edit Expert: {{ $expert->name }}" subtitle="Update expert trader details">
    <x-slot name="actions">
        <a href="{{ route('admin.experts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium">Back to List</a>
    </x-slot>
</x-admin.page-header>

<form action="{{ route('admin.experts.update', $expert->id) }}" method="POST" enctype="multipart/form-data" class="mt-6">
    @csrf
    @method('PUT')

    <x-admin.card>
        {{-- Basic Info --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Basic Information</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Name" for="name" :error="$errors->first('name')" required>
                    <input type="text" name="name" id="name" value="{{ old('name', $expert->name) }}" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Area of Expertise" for="area_of_expertise" :error="$errors->first('area_of_expertise')" required>
                    <select name="area_of_expertise" id="area_of_expertise" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @foreach(['Crypto', 'Forex', 'Stocks', 'Indices', 'Commodities', 'Mixed'] as $area)
                            <option value="{{ $area }}" {{ old('area_of_expertise', $expert->area_of_expertise) === $area ? 'selected' : '' }}>{{ $area }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
            </div>

            <div class="mt-5">
                <x-admin.form-group label="Bio" for="bio" :error="$errors->first('bio')">
                    <textarea name="bio" id="bio" rows="4" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary min-h-[120px] resize-y">{{ old('bio', $expert->bio) }}</textarea>
                </x-admin.form-group>
            </div>

            <div class="mt-5">
                <x-admin.form-group label="Profile Picture" for="profile_picture" :error="$errors->first('profile_picture')">
                    @if($expert->profile_picture)
                        <div class="mb-3">
                            <img src="{{ asset('storage/app/public/' . $expert->profile_picture) }}" alt="{{ $expert->name }}" class="w-20 h-20 rounded-lg object-cover">
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-border hover:border-primary rounded-lg p-6 text-center transition-colors">
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-content-secondary">
                        <p class="text-xs text-content-muted mt-2">Leave empty to keep current photo. JPG, JPEG, PNG — Max 2MB</p>
                    </div>
                </x-admin.form-group>
            </div>
        </fieldset>

        {{-- Trading Configuration --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Trading Configuration</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-admin.form-group label="Daily ROI (%)" for="daily_roi" :error="$errors->first('daily_roi')" required>
                    <input type="number" name="daily_roi" id="daily_roi" value="{{ old('daily_roi', $expert->daily_roi) }}" step="0.01" min="0.01" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Duration (days)" for="duration_days" :error="$errors->first('duration_days')" required>
                    <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $expert->duration_days) }}" min="1" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Win Rate (%)" for="win_rate" :error="$errors->first('win_rate')" required>
                    <input type="number" name="win_rate" id="win_rate" value="{{ old('win_rate', $expert->win_rate) }}" step="0.01" min="0" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Min Capital ($)" for="min_startup_capital" :error="$errors->first('min_startup_capital')" required>
                    <input type="number" name="min_startup_capital" id="min_startup_capital" value="{{ old('min_startup_capital', $expert->min_startup_capital) }}" step="0.01" min="0" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Max Capital ($)" for="max_capital" :error="$errors->first('max_capital')" helper="Leave empty for no limit">
                    <input type="number" name="max_capital" id="max_capital" value="{{ old('max_capital', $expert->max_capital) }}" step="0.01" min="0" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="Profit Share (%)" for="profit_share_percentage" :error="$errors->first('profit_share_percentage')" required>
                    <input type="number" name="profit_share_percentage" id="profit_share_percentage" value="{{ old('profit_share_percentage', $expert->profit_share_percentage) }}" step="0.01" min="0" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>
            </div>
        </fieldset>

        {{-- Display Stats --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Display Stats (Admin-Controlled)</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Followers Count" for="followers_count" :error="$errors->first('followers_count')">
                    <input type="number" name="followers_count" id="followers_count" value="{{ old('followers_count', $expert->followers_count) }}" min="0" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="Total ROI (%)" for="total_roi" :error="$errors->first('total_roi')">
                    <input type="number" name="total_roi" id="total_roi" value="{{ old('total_roi', $expert->total_roi) }}" step="0.01" min="0" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>
            </div>
        </fieldset>

        {{-- Status --}}
        <fieldset class="mb-6">
            <legend class="text-base font-semibold text-content mb-4">Status</legend>
            <label class="flex items-center gap-3 cursor-pointer" x-data="{ on: {{ old('is_active', $expert->is_active) ? 'true' : 'false' }} }">
                <input type="checkbox" name="is_active" class="sr-only" x-model="on" :checked="on">
                <div class="relative w-11 h-6 rounded-full transition-colors" :class="on ? 'bg-primary' : 'bg-surface-alt'" @click="on = !on">
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform" :class="on ? 'translate-x-5' : ''"></div>
                </div>
                <span class="text-sm text-content" x-text="on ? 'Active' : 'Inactive'"></span>
            </label>
        </fieldset>

        {{-- Submit --}}
        <div class="flex items-center gap-3 pt-4 border-t border-border">
            <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium">Update Expert</button>
            <a href="{{ route('admin.experts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-6 py-2.5 text-sm font-medium">Cancel</a>
        </div>
    </x-admin.card>
</form>

@endsection
