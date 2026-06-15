@extends('layouts.admin-dash')
@section('title', 'Payment Settings')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Payment Settings" subtitle="Manage payment methods, gateways, preferences, and transfer settings." />

    {{-- Validation Errors --}}
    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Tabs --}}
    <div x-data="{ activeTab: 'methods' }" class="mt-6">
        {{-- Tab Navigation --}}
        <div class="flex gap-1 border-b border-border overflow-x-auto">
            <button @click="activeTab = 'methods'"
                :class="activeTab === 'methods' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Payment Methods
            </button>
            <button @click="activeTab = 'preference'"
                :class="activeTab === 'preference' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Payment Preference
            </button>
            <button @click="activeTab = 'coinpayment'"
                :class="activeTab === 'coinpayment' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Coinpayment
            </button>
            <button @click="activeTab = 'gateways'"
                :class="activeTab === 'gateways' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Gateways
            </button>
            <button @click="activeTab = 'transfer'"
                :class="activeTab === 'transfer' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Transfer
            </button>
        </div>

        {{-- Tab Panels --}}
        <div class="mt-6">
            {{-- Payment Methods Tab --}}
            <div x-show="activeTab === 'methods'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.PaymentSettings.deposit')
            </div>

            {{-- Payment Preference Tab --}}
            <div x-show="activeTab === 'preference'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.PaymentSettings.withdrawal')
            </div>

            {{-- Coinpayment Tab --}}
            <div x-show="activeTab === 'coinpayment'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.PaymentSettings.coinpayment')
            </div>

            {{-- Gateways Tab --}}
            <div x-show="activeTab === 'gateways'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.PaymentSettings.gateway')
            </div>

            {{-- Transfer Tab --}}
            <div x-show="activeTab === 'transfer'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.PaymentSettings.transfers')
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

    ajaxSubmit('#paypreform', "{{ route('paypreference') }}");
    ajaxSubmit('#coinpayform', "{{ route('updatecpd') }}");
    ajaxSubmit('#gatewayform', "{{ route('updategateway') }}");
    ajaxSubmit('#trasfer', "{{ route('updatetransfer') }}");
</script>
@endpush
