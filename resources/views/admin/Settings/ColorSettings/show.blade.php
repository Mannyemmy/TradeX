@extends('layouts.admin-dash')
@section('title', 'Color Settings')

@section('content')
    <x-admin.page-header title="Color Settings" subtitle="Customize the colors used across your website, dashboard, and auth pages." />

    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    <div x-data="colorSettings()" class="mt-6 space-y-6">

        {{-- Live Preview Strip --}}
        <div class="bg-surface-card rounded-xl border border-border shadow-sm p-5">
            <h3 class="text-sm font-semibold text-content mb-3">Live Preview</h3>
            <div class="flex flex-wrap gap-3">
                <template x-for="(val, key) in colors" :key="key">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-10 h-10 rounded-lg border border-border shadow-sm" :style="'background-color:' + val"></div>
                        <span class="text-[10px] text-content-muted truncate max-w-[60px]" x-text="key.replace(/_/g, ' ')"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Color Form --}}
        <form @submit.prevent="saveColors()" id="colorForm">
            @csrf
            @method('PUT')

            {{-- Primary Colors --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-sm p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-content">Primary Colors</h3>
                        <p class="text-xs text-content-muted mt-0.5">Main accent color used for buttons, links, and highlights</p>
                    </div>
                    <button type="button" @click="resetGroup('primary')"
                        class="text-xs text-content-muted hover:text-danger transition-colors">Reset to Default</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-admin.color-field x-model="colors.primary_color" label="Primary" />
                    <x-admin.color-field x-model="colors.primary_light" label="Primary Light" />
                    <x-admin.color-field x-model="colors.primary_dark" label="Primary Dark" />
                </div>
            </div>

            {{-- Signal Colors --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-sm p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-content">Signal Colors</h3>
                        <p class="text-xs text-content-muted mt-0.5">Used for status indicators — profit, loss, warnings, and informational messages</p>
                    </div>
                    <button type="button" @click="resetGroup('signal')"
                        class="text-xs text-content-muted hover:text-danger transition-colors">Reset to Default</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-admin.color-field x-model="colors.gain_color" label="Gain / Profit" />
                    <x-admin.color-field x-model="colors.loss_color" label="Loss / Danger" />
                    <x-admin.color-field x-model="colors.warning_color" label="Warning" />
                    <x-admin.color-field x-model="colors.info_color" label="Info" />
                </div>
            </div>

            {{-- Surface Colors --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-sm p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-content">Surface Colors</h3>
                        <p class="text-xs text-content-muted mt-0.5">Dark background colors for the user dashboard and auth pages</p>
                    </div>
                    <button type="button" @click="resetGroup('surface')"
                        class="text-xs text-content-muted hover:text-danger transition-colors">Reset to Default</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-admin.color-field x-model="colors.surface_base" label="Base Background" />
                    <x-admin.color-field x-model="colors.surface_raised" label="Raised / Cards" />
                    <x-admin.color-field x-model="colors.surface_overlay" label="Overlay / Modals" />
                    <x-admin.color-field x-model="colors.surface_border" label="Border" />
                    <x-admin.color-field x-model="colors.surface_border_light" label="Border Light" />
                </div>
            </div>

            {{-- Content / Text Colors --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-sm p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-content">Content / Text Colors</h3>
                        <p class="text-xs text-content-muted mt-0.5">Text colors used on dark backgrounds (dashboard, auth pages)</p>
                    </div>
                    <button type="button" @click="resetGroup('content')"
                        class="text-xs text-content-muted hover:text-danger transition-colors">Reset to Default</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-admin.color-field x-model="colors.content_primary" label="Primary Text" />
                    <x-admin.color-field x-model="colors.content_secondary" label="Secondary Text" />
                    <x-admin.color-field x-model="colors.content_tertiary" label="Tertiary Text" />
                </div>
            </div>

            {{-- Body Colors (Public Pages) --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-sm p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-content">Body Colors (Public Pages)</h3>
                        <p class="text-xs text-content-muted mt-0.5">Light-theme colors for the public marketing pages (homepage, about, etc.)</p>
                    </div>
                    <button type="button" @click="resetGroup('body')"
                        class="text-xs text-content-muted hover:text-danger transition-colors">Reset to Default</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-admin.color-field x-model="colors.body_bg" label="Background" />
                    <x-admin.color-field x-model="colors.body_text" label="Body Text" />
                    <x-admin.color-field x-model="colors.body_muted" label="Muted Text" />
                    <x-admin.color-field x-model="colors.body_border" label="Body Border" />
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-primary-foreground text-sm font-medium rounded-lg hover:bg-primary-hover transition-colors"
                    :disabled="saving">
                    <svg x-show="saving" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="saving ? 'Saving...' : 'Save Colors'"></span>
                </button>
                <button type="button" @click="resetGroup('all')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-surface-alt text-content-secondary text-sm font-medium rounded-lg hover:bg-surface border border-border transition-colors">
                    Reset All to Defaults
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function colorSettings() {
    return {
        saving: false,
        defaults: @json($defaults),
        colors: {
            primary_color: '{{ $themeColors->primary_color ?? '#2E5C8A' }}',
            primary_light: '{{ $themeColors->primary_light ?? '#5DADE2' }}',
            primary_dark: '{{ $themeColors->primary_dark ?? '#0F3A6E' }}',
            gain_color: '{{ $themeColors->gain_color ?? '#1A3A7F' }}',
            loss_color: '{{ $themeColors->loss_color ?? '#EF4444' }}',
            warning_color: '{{ $themeColors->warning_color ?? '#F59E0B' }}',
            info_color: '{{ $themeColors->info_color ?? '#3B82F6' }}',
            surface_base: '{{ $themeColors->surface_base ?? '#0F1115' }}',
            surface_raised: '{{ $themeColors->surface_raised ?? '#161A1E' }}',
            surface_overlay: '{{ $themeColors->surface_overlay ?? '#1C2127' }}',
            surface_border: '{{ $themeColors->surface_border ?? '#2A2F36' }}',
            surface_border_light: '{{ $themeColors->surface_border_light ?? '#363C44' }}',
            content_primary: '{{ $themeColors->content_primary ?? '#E8EAED' }}',
            content_secondary: '{{ $themeColors->content_secondary ?? '#9AA0AB' }}',
            content_tertiary: '{{ $themeColors->content_tertiary ?? '#6B7280' }}',
            body_bg: '{{ $themeColors->body_bg ?? '#F5F7F9' }}',
            body_text: '{{ $themeColors->body_text ?? '#1F2937' }}',
            body_muted: '{{ $themeColors->body_muted ?? '#6B7280' }}',
            body_border: '{{ $themeColors->body_border ?? '#E5E7EB' }}',
        },

        saveColors() {
            this.saving = true;
            $.ajax({
                url: '{{ route("admin.update-colors") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    ...this.colors
                },
                success: (response) => {
                    this.saving = false;
                    if (response.status === 200) {
                        Swal.fire({ icon: 'success', title: 'Success', text: response.success, timer: 3000, showConfirmButton: false });
                    }
                },
                error: (xhr) => {
                    this.saving = false;
                    let msg = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            });
        },

        resetGroup(group) {
            Swal.fire({
                title: 'Reset ' + (group === 'all' ? 'All Colors' : group.charAt(0).toUpperCase() + group.slice(1) + ' Colors') + '?',
                text: 'This will revert to the default color values.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'rgb(var(--primary))',
                cancelButtonColor: 'rgb(var(--secondary))',
                confirmButtonText: 'Yes, reset'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.reset-colors") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            group: group
                        },
                        success: (response) => {
                            if (response.status === 200) {
                                // Update local state with returned defaults
                                for (const [key, val] of Object.entries(response.colors)) {
                                    if (this.colors.hasOwnProperty(key)) {
                                        this.colors[key] = val;
                                    }
                                }
                                Swal.fire({ icon: 'success', title: 'Reset', text: response.success, timer: 2000, showConfirmButton: false });
                            }
                        },
                        error: () => {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Could not reset colors.' });
                        }
                    });
                }
            });
        }
    }
}
</script>
@endpush
