@extends('layouts.admin-dash')
@section('title', 'Referral & Bonus Settings')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Referral & Bonus Settings" subtitle="Configure referral commissions and user bonuses." />

    {{-- Validation Errors --}}
    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Tabs --}}
    <div x-data="{ activeTab: 'referral' }" class="mt-6">
        {{-- Tab Navigation --}}
        <div class="flex gap-1 border-b border-border overflow-x-auto">
            <button @click="activeTab = 'referral'"
                :class="activeTab === 'referral' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Referral Bonus
            </button>
            <button @click="activeTab = 'other'"
                :class="activeTab === 'other' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Other Bonus(es)
            </button>
        </div>

        {{-- Tab Panels --}}
        <div class="mt-6">
            <div x-show="activeTab === 'referral'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.ReferralSettings.referral')
            </div>
            <div x-show="activeTab === 'other'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.ReferralSettings.other-bonus')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: type === 'success' ? 'rgb(var(--success-light))' : 'rgb(var(--danger-light))',
            color: type === 'success' ? 'rgb(var(--success))' : 'rgb(var(--danger))',
        });
    }

    function ajaxSubmit(formId, url) {
        $(formId).on('submit', function() {
            $.ajax({
                url: url,
                type: 'POST',
                data: $(formId).serialize(),
                success: function(response) {
                    if (response.status === 200) {
                        showToast(response.success, 'success');
                    }
                },
                error: function(error) {
                    showToast('An error occurred. Please try again.', 'error');
                    console.log(error);
                },
            });
        });
    }

    ajaxSubmit('#refform', "{{ route('updaterefbonus') }}");
    ajaxSubmit('#bonusform', "{{ route('otherbonus') }}");
</script>
@endpush
