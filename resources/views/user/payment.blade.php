@extends('layouts.dash1')
@section('title', $title)
@section('content')

{{-- ── Alerts ── --}}
<x-danger-alert />
<x-success-alert />
<x-alert />

{{-- ── Ticker tape ── --}}
@include('user.partials.ticker-tape')

{{-- ── Quick nav ── --}}
@include('user.partials.quick-nav')

{{-- ── Page header ── --}}
@include('user.partials.page-header', ['title' => session('loan_repayment') ? 'Loan Repayment' : 'Make Payment', 'subtitle' => session('loan_repayment') ? 'Complete your loan installment payment' : 'Complete your deposit transaction'])

{{-- ── Loan repayment context banner ── --}}
@if(session('loan_repayment'))
<div class="max-w-3xl mx-auto">
    <div class="rounded-xl bg-primary/10 border border-primary/20 p-4 flex items-start gap-3">
        <x-icon name="information-circle" class="w-5 h-5 text-primary mt-0.5 shrink-0" />
        <div>
            <p class="text-sm font-semibold text-primary">Loan Repayment — Installment #{{ session('loan_installment_number') }}</p>
            <p class="text-xs text-content-secondary mt-1">
                This deposit will be applied to your loan repayment once verified by admin.
                After completing payment, upload your proof below.
            </p>
        </div>
    </div>
</div>
@endif

<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Payment summary card ── --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    @if (!empty($payment_mode->img_url))
                        <img src="{{ $payment_mode->img_url }}" alt="{{ $payment_mode->name }}" class="w-8 h-8 object-contain">
                    @else
                        @include('components.icons.wallet', ['class' => 'w-6 h-6 text-primary'])
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-content-primary">{{ $payment_mode->name }}</h3>
                    <p class="text-sm text-content-tertiary">Payment method</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-primary">@money($amount)</p>
                <p class="text-xs text-content-tertiary">Amount due</p>
            </div>
        </div>
    </div>

    {{-- ── Payment body ── --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6">

        {{-- ── Automatic Cryptopayment QR code (Coinpayments completion page) ── --}}
        @if ($title == 'Complete Payment')
            <div class="text-center space-y-4 py-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium">
                    @include('components.icons.information-circle', ['class' => 'w-4 h-4'])
                    Awaiting Payment
                </div>
                <h4 class="text-content-primary font-medium">
                    Send <span class="text-primary font-bold">{{ $amount }}</span> to the address below or scan the {{ $coin }} QR code.
                </h4>
                <div x-data="{ copied: false }" class="space-y-3">
                    <div class="bg-surface-overlay rounded-lg p-4 border border-surface-border-light">
                        <p class="text-primary font-mono text-sm break-all select-all" id="qrAddress">{{ $p_address }}</p>
                    </div>
                    <button @click="navigator.clipboard.writeText($el.dataset.addr); copied = true; setTimeout(() => copied = false, 2000)"
                            data-addr="{{ $p_address }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary/10 text-primary text-sm font-medium hover:bg-primary/20 transition">
                        @include('components.icons.copy', ['class' => 'w-4 h-4'])
                        <span x-text="copied ? 'Copied!' : 'Copy Address'"></span>
                    </button>
                </div>
                <div class="inline-block p-3 bg-white rounded-xl">
                    <img width="200" height="200" alt="Payment QR code" src="{{ $p_qrcode }}" class="rounded-lg">
                </div>
                <p class="text-xs text-content-tertiary max-w-sm mx-auto">
                    You can exit this page after completing payment. The system will track your payment and update your account automatically.
                </p>
            </div>
        @else
            {{-- ── Standard payment flow ── --}}
            @php
                if ($payment_mode->name == 'Bitcoin') {
                    $coin = 'BTC';
                } elseif ($payment_mode->name == 'Litecoin') {
                    $coin = 'LTC';
                } elseif ($payment_mode->name == 'Ethereum') {
                    $coin = 'ETH';
                } elseif ($payment_mode->name == 'BUSD') {
                    $coin = 'BUSD';
                } else {
                    $coin = 'USDT.TRC20';
                }
            @endphp

            <div class="space-y-6">
                {{-- Payment instruction --}}
                <div class="flex items-start gap-3 p-4 rounded-lg bg-primary/5 border border-primary/20">
                    @include('components.icons.information-circle', ['class' => 'w-5 h-5 text-primary mt-0.5 shrink-0'])
                    <p class="text-sm text-content-secondary">
                        You are to make payment of <strong class="text-primary">@money($amount)</strong> using your selected payment method.
                    </p>
                </div>

                {{-- Barcode image if present --}}
                @if (!empty($payment_mode->barcode))
                    <div class="flex justify-center">
                        <div class="p-3 bg-white rounded-xl inline-block">
                            <img src="{{ asset('storage/app/public/'.$payment_mode->barcode) }}" alt="QR Code" class="max-w-[200px]">
                        </div>
                    </div>
                @endif

                {{-- ── Auto crypto payments (skip for loan repayments — always use manual) ── --}}
                @if ($settings->deposit_option != 'manual' && !session('loan_repayment'))
                    @if (in_array($payment_mode->name, ['Bitcoin', 'Litecoin', 'Ethereum', 'USDT', 'BUSD']))
                        @if ($payment_mode->name == 'USDT' && $settings->auto_merchant_option == 'Binance' && $settings->deposit_option == 'auto')
                            <livewire:user.crypto-payment />
                        @else
                            <div class="text-center">
                                <a href="{{ url('dashboard/cpay') }}/{{ $amount }}/{{ $coin }}/{{ Auth::user()->id }}/new"
                                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                                    @include('components.icons.wallet', ['class' => 'w-5 h-5'])
                                    Pay Via Coinpayment
                                </a>
                            </div>
                        @endif
                    @else
                        @if ((!empty($payment_mode->barcode) || $payment_mode->barcode != null) && $payment_mode->methodtype != 'currency')
                            <div class="flex justify-center">
                                <div class="p-3 bg-white rounded-xl inline-block">
                                    <img src="{{ asset('storage/' . $payment_mode->barcode) }}" alt="Payment QR" class="max-w-[280px]">
                                </div>
                            </div>
                        @endif
                    @endif
                @endif

                {{-- ── Crypto wallet address (non-auto or manual, always for loan repayments) ── --}}
                @if ($payment_mode->methodtype != 'currency')
                    @if (in_array($payment_mode->name, ['Bitcoin', 'Litecoin', 'Ethereum', 'USDT', 'BUSD']) && $settings->deposit_option != 'manual' && !session('loan_repayment'))
                        {{-- Auto crypto — no wallet address display needed --}}
                    @else
                        <div class="space-y-3">
                            <h4 class="text-sm font-semibold text-content-primary">{{ $payment_mode->name }} Address</h4>
                            <div x-data="{ copied: false }" class="flex items-stretch gap-2">
                                <input type="text" value="{{ $payment_mode->wallet_address }}" id="myInput" readonly
                                       class="flex-1 bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary font-mono select-all focus:outline-none">
                                <button @click="navigator.clipboard.writeText($refs.walletAddr.value); copied = true; setTimeout(() => copied = false, 2000)"
                                        x-ref="copyBtn"
                                        class="px-4 rounded-lg border border-primary/30 bg-primary/10 text-primary hover:bg-primary/20 transition flex items-center gap-2">
                                    @include('components.icons.copy', ['class' => 'w-4 h-4'])
                                    <span class="text-sm font-medium" x-text="copied ? 'Copied!' : 'Copy'"></span>
                                </button>
                            </div>
                            @if (!empty($payment_mode->network))
                                <p class="text-xs text-content-tertiary">
                                    <span class="font-semibold text-content-secondary">Network:</span> {{ $payment_mode->network }}
                                </p>
                            @endif
                        </div>
                    @endif

                @else
                    {{-- ── Currency payment methods ── --}}
                    <div class="space-y-4">
                        <h4 class="text-base font-semibold text-content-primary">{{ $payment_mode->name }}</h4>

                        @if ($payment_mode->defaultpay == 'yes')

                            {{-- Paystack --}}
                            @if ($payment_mode->name == 'Credit Card' && $settings->credit_card_provider == 'Paystack')
                                @php $payamount = $amount * 100; @endphp
                                <div id="paystack">
                                    <form method="POST" action="{{ route('pay.paystack') }}" accept-charset="UTF-8" role="form">
                                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                        <input type="hidden" name="amount" value="{{ $payamount }}">
                                        <input type="hidden" name="currency" value="{{ $settings->s_currency }}">
                                        <input type="hidden" name="metadata" value="{{ json_encode(['key_name' => 'value']) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit"
                                                class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                                            @include('components.icons.banknotes', ['class' => 'w-5 h-5'])
                                            Pay with Card
                                        </button>
                                    </form>
                                </div>
                            @endif

                            {{-- Flutterwave --}}
                            @if ($payment_mode->name == 'Credit Card' && $settings->credit_card_provider == 'Flutterwave')
                                <form method="POST" action="{{ route('paybyflutterwave') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="name" value="{{ Auth::user()->name }}" />
                                    <input name="email" type="hidden" value="{{ Auth::user()->email }}" />
                                    <input name="phone" type="hidden" value="{{ Auth::user()->phone }}" />
                                    <input name="amount" type="hidden" value="{{ $amount }}" />
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                                        @include('components.icons.banknotes', ['class' => 'w-5 h-5'])
                                        Pay with Card
                                    </button>
                                </form>
                            @endif

                            {{-- Stripe --}}
                            @if ($payment_mode->name == 'Credit Card' && $settings->credit_card_provider == 'Stripe')
                                <form id="payment-form" class="space-y-4">
                                    @csrf
                                    <div id="card-element" class="bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3.5"></div>
                                    <button id="stripesubmit" type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg id="spinner" class="hidden animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        <span id="buttontext">Pay Now</span>
                                    </button>
                                </form>
                                <div id="stripesuccess" class="hidden p-4 rounded-lg bg-gain/10 text-gain text-center font-medium">
                                    Payment completed, redirecting&hellip;
                                </div>
                                <form id="selectform" method="POST" action="javascript:void(0)">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                </form>
                            @endif

                            {{-- PayPal --}}
                            @if ($payment_mode->name == 'Paypal')
                                <div class="rounded-lg bg-surface-overlay border border-surface-border-light p-4">
                                    @include('includes.paypal')
                                </div>
                            @endif

                            {{-- Bank Transfer --}}
                            @if ($payment_mode->name == 'Bank Transfer')
                                @include('user.partials._bank-details', ['payment_mode' => $payment_mode])
                            @endif

                        @else
                            {{-- Non-default pay: show bank details --}}
                            @include('user.partials._bank-details', ['payment_mode' => $payment_mode])
                        @endif
                    </div>
                @endif

                {{-- ── Proof upload: auto mode (bank transfer or non-default) ── --}}
                @if (
                    !session('loan_repayment') && (
                        ($settings->deposit_option == 'auto' && $payment_mode->name == 'Bank Transfer') ||
                        ($settings->deposit_option == 'auto' && $payment_mode->defaultpay != 'yes')
                    )
                )
                    <div class="border-t border-surface-border pt-6">
                        <h4 class="text-sm font-semibold text-content-primary mb-3">Upload Payment Proof</h4>
                        <form method="post" action="{{ route('savedeposit') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <p class="text-sm text-content-secondary">Upload your payment proof after completing the transaction.</p>
                            <input type="file" name="proof" required
                                   class="block w-full text-sm text-content-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition cursor-pointer">
                            <input type="hidden" name="amount" value="{{ $amount }}">
                            <input type="hidden" name="paymethd_method" value="{{ $payment_mode->name }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                                @include('components.icons.arrow-up-tray', ['class' => 'w-5 h-5'])
                                Submit Payment
                            </button>
                        </form>
                    </div>
                @endif

                {{-- ── Proof upload: manual mode ── --}}
                @if (!session('loan_repayment') && $settings->deposit_option == 'manual' && $payment_mode->name != 'Credit Card' && $payment_mode->name != 'Paypal')
                    <div class="border-t border-surface-border pt-6">
                        <h4 class="text-sm font-semibold text-content-primary mb-3">Upload Payment Proof</h4>
                        <form method="post" action="{{ route('savedeposit') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <p class="text-sm text-content-secondary">Upload your payment proof after completing the transaction.</p>
                            <input type="file" name="proof" required
                                   class="block w-full text-sm text-content-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition cursor-pointer">
                            <input type="hidden" name="amount" value="{{ $amount }}">
                            <input type="hidden" name="paymethd_method" value="{{ $payment_mode->name }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                                @include('components.icons.check-circle', ['class' => 'w-5 h-5'])
                                Mark as Completed
                            </button>
                        </form>
                    </div>
                @endif

                {{-- ── Proof upload: loan repayment (always shown) ── --}}
                @if (session('loan_repayment'))
                    <div class="border-t border-surface-border pt-6">
                        <h4 class="text-sm font-semibold text-content-primary mb-3">Upload Payment Proof</h4>
                        <form method="post" action="{{ route('savedeposit') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <p class="text-sm text-content-secondary">Upload your payment proof after completing the transaction. Your loan installment will be updated once admin verifies this deposit.</p>
                            <input type="file" name="proof" required
                                   class="block w-full text-sm text-content-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition cursor-pointer">
                            <input type="hidden" name="amount" value="{{ $amount }}">
                            <input type="hidden" name="paymethd_method" value="{{ $payment_mode->name }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                                @include('components.icons.arrow-up-tray', ['class' => 'w-5 h-5'])
                                Submit Loan Payment
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

@endsection

@section('scripts')
{{-- ── Stripe integration (only loaded when Stripe is the payment provider) ── --}}
@if (isset($payment_mode) && $payment_mode->methodtype == 'currency' && $payment_mode->name == 'Credit Card' && isset($settings) && $settings->credit_card_provider == 'Stripe' && $title != 'Complete Payment')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe(@json($settings->s_p_k));
    var elements = stripe.elements();
    var style = {
        base: {
            color: '#E8EAED',
            fontFamily: 'Inter, sans-serif',
            fontSize: '14px',
            '::placeholder': { color: '#6B7280' }
        }
    };
    const paybtn = document.querySelector('#stripesubmit');
    paybtn.disabled = true;

    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    card.on('change', function(event) {
        if (event.error) {
            Swal.fire({ icon: 'error', title: 'Card Error', text: event.error.message, background: '#161A1E', color: '#E8EAED' });
            paybtn.disabled = true;
        } else {
            paybtn.disabled = false;
        }
    });

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(ev) {
        paybtn.disabled = true;
        ev.preventDefault();
        document.getElementById('spinner').classList.remove('hidden');
        document.getElementById('buttontext').classList.add('hidden');

        var clientSecret = @json($intent);
        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: card,
                billing_details: { name: @json(Auth::user()->name) }
            }
        }).then(function(result) {
            if (result.error) {
                Swal.fire({ icon: 'error', title: 'Payment Failed', text: 'There was an error processing your payment. Please try again from the deposit page.', background: '#161A1E', color: '#E8EAED' });
                document.getElementById('spinner').classList.add('hidden');
                document.getElementById('buttontext').classList.remove('hidden');
                paybtn.disabled = false;
            } else if (result.paymentIntent.status === 'succeeded') {
                document.getElementById('stripesuccess').classList.remove('hidden');
                fetch(@json(url('/dashboard/submit-stripe-payment')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({ amount: @json($amount) })
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({ icon: 'success', title: 'Success', text: data.success || 'Payment completed!', background: '#161A1E', color: '#E8EAED' });
                    setTimeout(() => window.location.replace(@json(route('accounthistory'))), 3000);
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error submitting payment data.', background: '#161A1E', color: '#E8EAED' });
                });
            }
        });
    });
</script>
@endif
@endsection
