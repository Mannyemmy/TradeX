@extends('layouts.dash1')
@section('title', $title)

@section('content')
@php($u = Auth::user())
<div class="max-w-3xl mx-auto">
    <div class="rounded-2xl border border-body-border bg-surface-raised p-6 sm:p-8">
        <h2 class="text-lg font-semibold text-content-primary mb-1">Withdrawal Account Details</h2>
        <p class="text-sm text-content-secondary mb-6">These details are used to process your withdrawals. Keep them accurate.</p>

        <div id="acct-alert" class="hidden mb-4 rounded-lg px-4 py-3 text-sm"></div>

        <form id="updateacct-form" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ $u->bank_name }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Account Name</label>
                    <input type="text" name="account_name" value="{{ $u->account_name }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Account Number</label>
                    <input type="text" name="account_no" value="{{ $u->account_number }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Swift Code</label>
                    <input type="text" name="swiftcode" value="{{ $u->swift_code }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">BTC Address</label>
                    <input type="text" name="btc_address" value="{{ $u->btc_address }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">ETH Address</label>
                    <input type="text" name="eth_address" value="{{ $u->eth_address }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">LTC Address</label>
                    <input type="text" name="ltc_address" value="{{ $u->ltc_address }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">USDT Address</label>
                    <input type="text" name="usdt_address" value="{{ $u->usdt_address }}" class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark transition">Save details</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('updateacct-form').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = e.target;
        var alertBox = document.getElementById('acct-alert');
        fetch("{{ route('updateacount') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: new FormData(form),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            alertBox.className = 'mb-4 rounded-lg px-4 py-3 text-sm bg-gain/10 text-gain border border-gain/30';
            alertBox.textContent = (data && data.success) ? data.success : 'Saved.';
            alertBox.classList.remove('hidden');
        })
        .catch(function () {
            alertBox.className = 'mb-4 rounded-lg px-4 py-3 text-sm bg-loss/10 text-loss border border-loss/30';
            alertBox.textContent = 'Something went wrong. Please try again.';
            alertBox.classList.remove('hidden');
        });
    });
</script>
@endsection
