@extends('layouts.base')

@section('title', 'Risk Warning')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-body-bg py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text">Risk Warning</h1>
        <div class="flex items-center justify-center gap-2 mt-3 text-sm text-body-muted">
            <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-primary">Risk Warning</span>
        </div>
    </div>
</section>

{{-- ===== CONTENT ===== --}}
<section class="bg-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="bg-body-bg rounded-xl border border-body-border p-6 md:p-10">
            <div class="flex gap-3 mb-6">
                <a href="{{ route('terms') }}" class="text-sm font-medium bg-primary-subtle text-primary px-3 py-1 rounded-full hover:bg-primary hover:text-white transition">Risk Warning</a>
                <a href="{{ route('privacy') }}" class="text-sm font-medium bg-body-bg text-body-muted px-3 py-1 rounded-full border border-body-border hover:text-primary transition">Privacy Policy</a>
            </div>

            <article class="prose prose-sm max-w-none text-body-muted leading-relaxed space-y-6">
                <h3 class="text-xl font-bold text-body-text">RISK WARNING</h3>
                <p>Trading foreign exchange on margin carries a high level of risk, and may not be suitable for all investors. Before deciding to trade foreign exchange, you should carefully consider your investment objectives, level of experience, and risk appetite. There is a possibility that you may sustain a loss of some or all of your investment and therefore you should not invest money that you cannot afford to lose. You should be aware of all the risks associated with foreign exchange trading, and seek advice from an independent financial advisor if you have any doubts.</p>

                <h4 class="text-lg font-semibold text-body-text">Risks of investing in CFDs</h4>
                <p>CFDs, especially when highly leveraged (the higher the leverage of the CFD, the more risky it becomes), carry a very high level of risk. They are not standardized products. Different CFD providers have their own terms, conditions and costs. Therefore, generally, they are not suitable for most retail investors.</p>

                <h4 class="text-lg font-semibold text-body-text">Liquidity risk</h4>
                <p>Liquidity risk affects your ability to trade. It is the risk that your CFD or asset cannot be traded at the time you want to trade (to prevent a loss, or to make a profit).</p>

                <h4 class="text-lg font-semibold text-body-text">Execution risk</h4>
                <p>Execution risk is associated with the fact that trades may not take place immediately. For example, there might be a time lag between the moment you place your order and the moment it is executed.</p>

                <h4 class="text-lg font-semibold text-body-text">Internet Trading Risks</h4>
                <p>There are risks associated with utilizing an Internet-based deal execution trading system including, but not limited to, the failure of hardware, software, and Internet connection. Since {{ $settings->site_name }} does not control signal power, its reception or routing via Internet, configuration of your equipment or reliability of its connection, we cannot be responsible for communication failures, distortions or delays when trading via the Internet.</p>

                <h4 class="text-lg font-semibold text-body-text">Acknowledgement</h4>
                <p>The client acknowledges and declares that he has read, understood and thus accepts without any reservation the following:</p>

                <ul class="list-disc list-inside space-y-3 text-body-muted">
                    <li>The value of the Financial Instrument (including currency pair, CFDs, or any other derivative product) may decrease and the client may receive less money than originally invested or the value of the Financial Instruments may present high fluctuations.</li>
                    <li>Information on past performance of a Financial Instrument does not guarantee the present and/or future performance; the use of historic data does not constitute a binding or safe forecast as to the corresponding future return of the Financial Instruments to which such data refers.</li>
                    <li>Some Financial Instruments may not become immediately liquid due to various reasons such as reduced demand, and the Company may not be in a position to sell them or easily obtain information on the value of such Financial Instruments or the extent of any related or inherent risk concerning such Financial Instruments.</li>
                    <li>When a Financial Instrument is negotiated in a currency other than the currency of the client's country of residence, any changes in an exchange rate may have a negative effect on the Financial Instruments' value, price and performance.</li>
                    <li>A Financial Instrument in foreign markets may entail risks different than the usual risks in the markets at the client's country of residence. The prospect of profit or loss from transactions in foreign markets is also influenced by the exchange rate fluctuations.</li>
                </ul>
            </article>
        </div>
    </div>
</section>

@endsection
