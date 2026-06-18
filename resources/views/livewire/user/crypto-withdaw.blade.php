<div>
    {{-- Status Alert --}}
    @if (Session::has('status'))
        <div class="mb-4 flex items-start gap-3 p-4 rounded-lg bg-blue-500/10 border border-blue-500/20">
            <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-blue-300">{{ Session::get('status') }}</p>
        </div>
    @endif

    {{-- Error Alert --}}
    @if (Session::has('error'))
        <div class="mb-4 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-red-300">{{ Session::get('error') }}</p>
        </div>
    @endif

    {{-- Binance Warning --}}
    <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-amber-500/10 border border-amber-500/20">
        <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div class="text-sm text-amber-200">
            Our automatic USDT payment is powered by Binance. To receive your funds, please make sure you have a Binance account registered with the same email address on our platform. If you do not have one, please
            <a href="https://www.binance.com/en" target="_blank" class="text-amber-400 underline hover:text-amber-300">create an account</a>.
            <strong class="block mt-1 text-amber-300">NOTE: Do not proceed if you do not have a Binance account or have an account with a different email address.</strong>
        </div>
    </div>

    {{-- Withdrawal Form --}}
    <form wire:submit.prevent="withdraw" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-[#9AA0AB] mb-2">Enter Amount to Withdraw (@userCurrency)</label>
            <input type="number" wire:model="amount" name="amount" required placeholder="Enter Amount"
                class="w-full bg-[#1C2127] border border-[#2A2F36] rounded-lg px-4 py-3 text-[#E8EAED] placeholder-[#6B7280] focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
        </div>

        <input value="{{ $payment_mode }}" type="hidden" name="method">

        @if (Auth::user()->sendotpemail == 'Yes')
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-[#9AA0AB]">Enter OTP</label>
                    <div>
                        <a href="#" wire:click="requestOtp" wire:loading.remove wire:target="requestOtp"
                            class="inline-flex items-center gap-1.5 text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Request OTP
                        </a>
                        <span class="text-xs text-blue-400" wire:loading wire:target="requestOtp">Sending OTP to your email...</span>
                    </div>
                </div>
                <input type="text" wire:model="otpCode" required placeholder="Enter OTP"
                    class="w-full bg-[#1C2127] border border-[#2A2F36] rounded-lg px-4 py-3 text-[#E8EAED] placeholder-[#6B7280] focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                <p class="mt-1.5 text-xs text-[#6B7280]">OTP will be sent to your email when you request</p>
            </div>
        @endif

        <div>
            <button type="submit" wire:loading.attr="disabled" wire:target="withdraw"
                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                Complete Request
            </button>
        </div>
    </form>
</div>
