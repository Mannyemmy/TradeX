{{-- Live Trading Activity Toast --}}
<style>
@keyframes float-bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.live-activity-bounce {
    animation: float-bounce 2s ease-in-out infinite;
}
</style>
<div x-data="liveActivity()" x-init="start()" class="fixed top-20 left-6 z-50 pointer-events-none" style="max-width: 360px;">
    <div x-show="visible"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 -translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-6"
         class="live-activity-bounce pointer-events-auto relative bg-white rounded-xl shadow-xl p-4 flex items-start space-x-3">
        {{-- Pulse dot --}}
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gain opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-gain"></span>
        </span>
        {{-- Icon --}}
        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="current.iconBg">
            <template x-if="current.type === 'trade'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </template>
            <template x-if="current.type === 'deposit'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0 0l-4-4m4 4l4-4"/></svg>
            </template>
            <template x-if="current.type === 'withdrawal'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m0 0l-4 4m4-4l4 4"/></svg>
            </template>
            <template x-if="current.type === 'copy'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </template>
            <template x-if="current.type === 'signup'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </template>
        </div>
        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-primary uppercase tracking-wide mb-0.5" x-text="current.label"></p>
            <p class="text-sm font-medium text-body-text leading-snug" x-text="current.message"></p>
            <p class="text-xs text-body-muted mt-1" x-text="current.time"></p>
        </div>
        {{-- Close --}}
        <button @click="visible = false" class="flex-shrink-0 text-body-muted hover:text-body-text transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>

<script>
function liveActivity() {
    const cities = [
        'Sarajevo', 'London', 'New York', 'Tokyo', 'Dubai', 'Sydney',
        'Frankfurt', 'Singapore', 'Hong Kong', 'Toronto', 'Zürich',
        'Paris', 'Seoul', 'Mumbai', 'São Paulo', 'Lagos', 'Nairobi',
        'Istanbul', 'Cairo', 'Bangkok', 'Kuala Lumpur', 'Johannesburg',
        'Berlin', 'Madrid', 'Amsterdam', 'Stockholm', 'Vienna',
        'Warsaw', 'Prague', 'Budapest', 'Bucharest', 'Dublin',
        'Milan', 'Lisbon', 'Athens', 'Riyadh', 'Doha', 'Abu Dhabi',
        'Manila', 'Jakarta', 'Mexico City', 'Lima', 'Buenos Aires',
        'Santiago', 'Bogotá', 'Accra', 'Casablanca', 'Tunis'
    ];

    const assets = ['Gold', 'Silver', 'Bitcoin', 'Ethereum', 'EUR/USD', 'GBP/USD', 'USD/JPY', 'Crude Oil', 'Apple', 'Tesla', 'S&P 500', 'Solana', 'Platinum', 'Natural Gas', 'AUD/USD', 'NASDAQ', 'Ripple', 'Litecoin'];

    const cryptos = ['Bitcoin', 'Ethereum', 'USDT', 'Solana', 'Ripple', 'Litecoin', 'BNB', 'Cardano'];

    const experts = ['Alex M.', 'Sarah K.', 'James W.', 'Maria L.', 'David R.', 'Chen Y.', 'Omar H.', 'Elena V.'];

    function rand(arr) { return arr[Math.floor(Math.random() * arr.length)]; }
    function randAmount(min, max) { return (Math.floor(Math.random() * (max - min + 1)) + min).toLocaleString(); }
    function randTime() {
        var h = Math.floor(Math.random() * 24);
        var m = Math.floor(Math.random() * 60);
        return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
    }

    function generate() {
        var types = ['trade', 'trade', 'trade', 'deposit', 'deposit', 'withdrawal', 'copy', 'signup'];
        var type = rand(types);
        var city = rand(cities);

        switch(type) {
            case 'trade':
                var asset = rand(assets);
                var amount = randAmount(500, 250000);
                return {
                    type: 'trade',
                    label: 'Live Trading',
                    iconBg: 'bg-gain',
                    message: 'Trader from ' + city + ' just earned $' + amount + ' trading ' + asset,
                    time: randTime() + ' UTC'
                };
            case 'deposit':
                var crypto = rand(cryptos);
                var amount = randAmount(1000, 100000);
                return {
                    type: 'deposit',
                    label: 'New Deposit',
                    iconBg: 'bg-primary',
                    message: 'Investor from ' + city + ' deposited $' + amount + ' via ' + crypto,
                    time: randTime() + ' UTC'
                };
            case 'withdrawal':
                var amount = randAmount(2000, 150000);
                return {
                    type: 'withdrawal',
                    label: 'Withdrawal Processed',
                    iconBg: 'bg-info',
                    message: 'Trader from ' + city + ' withdrew $' + amount + ' successfully',
                    time: randTime() + ' UTC'
                };
            case 'copy':
                var expert = rand(experts);
                var amount = randAmount(5000, 200000);
                return {
                    type: 'copy',
                    label: 'Copy Trading',
                    iconBg: 'bg-warning',
                    message: 'Trader from ' + city + ' copied ' + expert + ' with $' + amount,
                    time: randTime() + ' UTC'
                };
            case 'signup':
                return {
                    type: 'signup',
                    label: 'New Member',
                    iconBg: 'bg-primary-dark',
                    message: 'New trader from ' + city + ' just joined the platform',
                    time: randTime() + ' UTC'
                };
        }
    }

    return {
        visible: false,
        current: generate(),
        start() {
            var self = this;
            // Initial delay before first notification
            setTimeout(function() { self.show(); }, 3000);
        },
        show() {
            var self = this;
            self.current = generate();
            self.visible = true;
            // Stay visible for 4 seconds
            setTimeout(function() {
                self.visible = false;
                // Wait 3-6 seconds before showing next
                var delay = 3000 + Math.floor(Math.random() * 3000);
                setTimeout(function() { self.show(); }, delay);
            }, 4000);
        }
    };
}
</script>
