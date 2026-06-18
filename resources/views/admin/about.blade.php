@extends('layouts.admin-dash')
@section('title', 'Platform Features Guide')

@section('content')

{{-- Page Header --}}
<x-admin.page-header title="TradexPro — Platform Features Guide" subtitle="A comprehensive overview of every feature for non-technical administrators and buyers." />

<div class="mt-6 space-y-6">

    {{-- Support Links --}}
    <div class="rounded-xl bg-gradient-to-r from-primary to-primary/80 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="md:w-1/4">
                <h4 class="text-lg font-bold text-content-inverse">Need Help?</h4>
                <p class="text-sm text-content-inverse/60">Get support & updates</p>
            </div>
            <div class="md:w-3/4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <a href="https://mydigitalmarkethub.com" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg bg-surface-card text-content hover:bg-surface-raised transition-colors shadow-sm">
                    <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Join WhatsApp
                </a>
                <a href="https://t.me/mydigitalmarkethub" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg bg-surface-card text-content hover:bg-surface-raised transition-colors shadow-sm">
                    <svg class="w-5 h-5 text-info" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    Join Telegram
                </a>
                <a href="https://mydigitalmarkethub.com/" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg bg-surface-card text-content hover:bg-surface-raised transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    Visit Website
                </a>
            </div>
        </div>
    </div>

    {{-- Version Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-admin.card>
            <div class="text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
                </div>
                <h5 class="font-bold text-content">Version Info</h5>
                <p class="text-content-muted text-sm">TradexProMax</p>
                <h3 class="text-xl font-bold text-primary mt-1">v5.0 Pro Max</h3>
            </div>
        </x-admin.card>
        <x-admin.card>
            <div class="text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/></svg>
                </div>
                <h5 class="font-bold text-content">Framework</h5>
                <p class="text-content-muted text-sm">Built with</p>
                <h3 class="text-xl font-bold text-danger mt-1">Laravel 8</h3>
            </div>
        </x-admin.card>
        <x-admin.card>
            <div class="text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <h5 class="font-bold text-content">Security</h5>
                <p class="text-content-muted text-sm">Enterprise-grade</p>
                <h3 class="text-xl font-bold text-success mt-1">2FA + KYC</h3>
            </div>
        </x-admin.card>
    </div>

    {{-- Quick Stats Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">85+</div>
            <div class="text-xs text-content-secondary mt-1">Tradable Assets</div>
        </div>
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">12+</div>
            <div class="text-xs text-content-secondary mt-1">Platform Modules</div>
        </div>
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">24</div>
            <div class="text-xs text-content-secondary mt-1">Email Types</div>
        </div>
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">5</div>
            <div class="text-xs text-content-secondary mt-1">Payment Gateways</div>
        </div>
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">6</div>
            <div class="text-xs text-content-secondary mt-1">Social Login Providers</div>
        </div>
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">35+</div>
            <div class="text-xs text-content-secondary mt-1">Admin Features</div>
        </div>
    </div>

    {{-- Platform Overview --}}
    <x-admin.card>
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-content">What is TradexPro?</h2>
                <p class="mt-2 text-sm text-content-secondary leading-relaxed">
                    TradexPro is a full-featured online trading and investment platform. It allows your users to trade multiple asset types (cryptocurrencies, forex, stocks, ETFs, and indices), invest in plans with automated returns, copy expert traders, buy pre-IPO shares, trade NFTs, take out loans, and much more — all from a single, modern dashboard.
                </p>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-content-secondary"><strong class="text-content">Admin-Controlled Outcomes</strong> — You decide each user's trading win rate</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-content-secondary"><strong class="text-content">85+ Tradable Assets</strong> — Crypto, forex, stocks, ETFs & indices with live prices</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-content-secondary"><strong class="text-content">All-in-One Platform</strong> — Trading, investments, copy trading, loans, NFTs, courses & signals</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-content-secondary"><strong class="text-content">Multiple Revenue Streams</strong> — Fees, interest, gas fees, course sales, signal subs</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-content-secondary"><strong class="text-content">Modern Dark Dashboard</strong> — Robinhood/Binance-style professional UI</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-content-secondary"><strong class="text-content">Separate Admin Panel</strong> — Own login, 2FA, comprehensive management tools</span>
                    </div>
                </div>
            </div>
        </div>
    </x-admin.card>

    {{-- Tabbed Feature Sections --}}
    <x-admin.tabs active="public">
        <x-slot name="tabs">
            <button @click="activeTab = 'public'" :class="activeTab === 'public' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Public Website
            </button>
            <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Security & Auth
            </button>
            <button @click="activeTab = 'financial'" :class="activeTab === 'financial' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Deposits & Withdrawals
            </button>
            <button @click="activeTab = 'trading'" :class="activeTab === 'trading' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Trading
            </button>
            <button @click="activeTab = 'investments'" :class="activeTab === 'investments' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Investments & Copy
            </button>
            <button @click="activeTab = 'modules'" :class="activeTab === 'modules' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                All Modules
            </button>
            <button @click="activeTab = 'admin'" :class="activeTab === 'admin' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Admin Panel
            </button>
            <button @click="activeTab = 'integrations'" :class="activeTab === 'integrations' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Integrations & Automation
            </button>
            <button @click="activeTab = 'enhanced'" :class="activeTab === 'enhanced' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                Enhanced Features
            </button>
        </x-slot>

        {{-- ===================== TAB: PUBLIC WEBSITE ===================== --}}
        <div x-show="activeTab === 'public'" x-transition>
            <div class="space-y-6">
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                        Public Marketing Website
                    </h3>
                    <p class="text-sm text-content-secondary mb-4">Your platform comes with a complete public-facing marketing website. All content is fully editable from the admin panel — no coding needed.</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-border">
                                    <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Page</th>
                                    <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">What It Shows</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr><td class="py-2.5 px-3 font-medium text-content">Home Page</td><td class="py-2.5 px-3 text-content-secondary">Hero banner, platform highlights, investment plans, FAQs, testimonials, recent deposit/withdrawal activity, and total user count</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">About Us</td><td class="py-2.5 px-3 text-content-secondary">Company mission, vision, values, and platform offerings</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Contact Us</td><td class="py-2.5 px-3 text-content-secondary">Contact form, office address, email, and working hours</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Markets / Pricing</td><td class="py-2.5 px-3 text-content-secondary">All asset classes (Forex, Stocks, Commodities, Indices, Crypto, ETFs/Bonds) with pricing</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Terms & Conditions</td><td class="py-2.5 px-3 text-content-secondary">Legal terms of service (editable from admin)</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Privacy Policy</td><td class="py-2.5 px-3 text-content-secondary">Data privacy policy (editable from admin)</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Risk Disclosure</td><td class="py-2.5 px-3 text-content-secondary">Trading risk information for regulatory compliance</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Security</td><td class="py-2.5 px-3 text-content-secondary">Platform security features (SSL, 2FA, segregated accounts)</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">FAQ / Legal Docs</td><td class="py-2.5 px-3 text-content-secondary">Frequently asked questions (manage from admin panel)</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Licenses</td><td class="py-2.5 px-3 text-content-secondary">Licensing and regulation information</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Careers / Services</td><td class="py-2.5 px-3 text-content-secondary">Career opportunities or additional services</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Web Trading Info</td><td class="py-2.5 px-3 text-content-secondary">Information about the web-based trading platform</td></tr>
                            </tbody>
                        </table>
                    </div>
                </x-admin.card>

                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                        Referral Link System
                    </h3>
                    <p class="text-sm text-content-secondary">
                        Every registered user gets a unique referral link (e.g., <span class="font-mono text-xs bg-surface-alt rounded px-1.5 py-0.5">yoursite.com/ref/12345</span>). When a visitor clicks this link and signs up, they are automatically linked to the referring user. The referrer earns commissions on their referral's deposits — up to <strong class="text-content">5 levels deep</strong> — with percentages you set in the admin panel.
                    </p>
                </x-admin.card>
            </div>
        </div>

        {{-- ===================== TAB: SECURITY & AUTH ===================== --}}
        <div x-show="activeTab === 'security'" x-transition>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">
                            <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                            User Registration
                        </h3>
                        <ul class="space-y-2 text-sm text-content-secondary">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span><strong class="text-content">Email & Password</strong> — Standard sign-up form with name, email, and password</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span><strong class="text-content">Social Login (6 providers)</strong> — Google, Facebook, Twitter, LinkedIn, GitHub, Bitbucket</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>Each social login provider can be enabled/disabled individually</span>
                            </li>
                        </ul>
                    </x-admin.card>

                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">
                            <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            Email Verification
                        </h3>
                        <ul class="space-y-2 text-sm text-content-secondary">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>New users must verify their email before accessing the dashboard (when enabled)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span><strong class="text-content">Toggle on/off</strong> from admin settings — useful during testing or VIP onboarding</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>Admins can manually verify any user's email with one click</span>
                            </li>
                        </ul>
                    </x-admin.card>

                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">
                            <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                            Two-Factor Authentication (2FA)
                        </h3>
                        <ul class="space-y-2 text-sm text-content-secondary">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>Users can enable 2FA for extra login security (Google Authenticator compatible)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>Email-based verification code as backup method</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>Toggleable per-user and globally in settings</span>
                            </li>
                        </ul>
                    </x-admin.card>

                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">
                            <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                            KYC Verification
                        </h3>
                        <ul class="space-y-2 text-sm text-content-secondary">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span><strong class="text-content">User submits KYC</strong> — Verification form with identity document upload (passport, ID, etc.)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span><strong class="text-content">Admin reviews & approves/rejects</strong> — One click with optional message</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>Can be <strong class="text-content">mandatory</strong> (blocks trading until verified) or <strong class="text-content">optional</strong></span>
                            </li>
                        </ul>
                    </x-admin.card>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">
                            <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                            IP Address Restrictions
                        </h3>
                        <p class="text-sm text-content-secondary">Whitelist specific IP addresses that are allowed to access the platform. Automatically blocks visitors from non-whitelisted IPs. Managed from the admin panel.</p>
                    </x-admin.card>

                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">
                            <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                            Separate Admin Authentication
                        </h3>
                        <p class="text-sm text-content-secondary">Completely separate login system at a custom URL. Includes admin-only 2FA, dedicated password recovery, and support for multiple admin accounts with block/unblock capability.</p>
                    </x-admin.card>
                </div>
            </div>
        </div>

        {{-- ===================== TAB: DEPOSITS & WITHDRAWALS ===================== --}}
        <div x-show="activeTab === 'financial'" x-transition>
            <div class="space-y-6">
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Deposit Methods
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead><tr class="border-b border-border">
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Payment Method</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Currencies</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">How It Works</th>
                            </tr></thead>
                            <tbody class="divide-y divide-border">
                                <tr><td class="py-2.5 px-3 font-medium text-content">Binance Pay</td><td class="py-2.5 px-3 text-content-secondary">USDT</td><td class="py-2.5 px-3 text-content-secondary">User scans QR code or pays via Binance app. Auto-verified and credited</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">CoinPayments</td><td class="py-2.5 px-3 text-content-secondary">BTC, ETH, LTC, BUSD, USDT</td><td class="py-2.5 px-3 text-content-secondary">Sends crypto to generated wallet address. Auto-confirmed after blockchain verification</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Manual Bank Transfer</td><td class="py-2.5 px-3 text-content-secondary">Any fiat</td><td class="py-2.5 px-3 text-content-secondary">User transfers to your bank and uploads payment proof. Admin reviews and approves</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Paystack</td><td class="py-2.5 px-3 text-content-secondary">NGN, GHS, ZAR, USD</td><td class="py-2.5 px-3 text-content-secondary">Card payments for African markets. Redirects to Paystack gateway, auto-confirms</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Flutterwave</td><td class="py-2.5 px-3 text-content-secondary">150+ currencies</td><td class="py-2.5 px-3 text-content-secondary">Card and mobile money across Africa. Redirects to Flutterwave, auto-confirms</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-success-light text-success">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Deposit Bonuses (configurable %)
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-info-light text-info">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                            5-Level Referral Commissions
                        </span>
                    </div>
                </x-admin.card>

                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                        Withdrawal Methods & Flow
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
                        @foreach(['Bitcoin (BTC)', 'Ethereum (ETH)', 'Litecoin (LTC)', 'BUSD', 'USDT (Tether)', 'Bank Transfer'] as $method)
                        <div class="bg-surface-alt rounded-lg p-3 text-center">
                            <span class="text-xs font-medium text-content">{{ $method }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-surface-alt rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-content mb-2">Withdrawal Flow:</h4>
                        <ol class="space-y-1.5 text-sm text-content-secondary list-decimal list-inside">
                            <li>User selects withdrawal method and enters amount + wallet/bank details</li>
                            <li>OTP (one-time password) sent to email for security verification</li>
                            <li>Optional <strong class="text-content">broker code</strong> required (admin sets per-user — control mechanism)</li>
                            <li>Request goes to "Pending" — admin reviews and approves/rejects</li>
                            <li>For crypto: funds sent via Binance Pay payout API (or manually)</li>
                            <li>User receives email notification of the result</li>
                        </ol>
                        <p class="mt-3 text-xs text-content-muted"><strong>Auto-Processing available:</strong> Enable in settings to skip admin approval for faster payouts.</p>
                    </div>
                </x-admin.card>

                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">Account Balance Types</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @php $balances = [
                            ['Main Account Balance', 'Primary trading and withdrawal balance', 'bg-primary/10 text-primary'],
                            ['ROI Balance', 'Returns earned from investment plans', 'bg-success-light text-success'],
                            ['Bonus Balance', 'Deposit bonuses and promotional credits', 'bg-warning-light text-warning'],
                            ['Referral Balance', 'Commissions from referred users', 'bg-info-light text-info'],
                            ['Frozen Balance', 'Funds temporarily locked (e.g., loan collateral)', 'bg-danger-light text-danger'],
                            ['Demo Balance', 'Virtual money for practice trading', 'bg-surface-alt text-content-secondary'],
                        ]; @endphp
                        @foreach($balances as [$name, $desc, $color])
                        <div class="flex items-start gap-3 p-3 rounded-lg {{ $color }} bg-opacity-50">
                            <div>
                                <div class="text-sm font-semibold">{{ $name }}</div>
                                <div class="text-xs opacity-80 mt-0.5">{{ $desc }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-content-secondary">
                        <strong class="text-content">User-to-User Transfers:</strong> Users can send funds to others by email or username. Enable/disable from admin settings.
                    </p>
                </x-admin.card>
            </div>
        </div>

        {{-- ===================== TAB: TRADING ===================== --}}
        <div x-show="activeTab === 'trading'" x-transition>
            <div class="space-y-6">
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        85+ Tradable Assets (5 Asset Classes)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead><tr class="border-b border-border">
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Asset Class</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Examples</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Price Source</th>
                            </tr></thead>
                            <tbody class="divide-y divide-border">
                                <tr><td class="py-2.5 px-3 font-medium text-content">Cryptocurrencies</td><td class="py-2.5 px-3 text-content-secondary">Bitcoin, Ethereum, Solana, Cardano, Dogecoin, and 30+ more</td><td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-light text-success">CoinGecko API (free)</span></td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Forex Pairs</td><td class="py-2.5 px-3 text-content-secondary">EUR/USD, GBP/USD, USD/JPY, AUD/USD, and 8+ more</td><td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-info-light text-info">TwelveData API</span></td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Stocks</td><td class="py-2.5 px-3 text-content-secondary">Apple, Tesla, Amazon, Google, Microsoft, and 9+ more</td><td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-info-light text-info">TwelveData API</span></td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">ETFs</td><td class="py-2.5 px-3 text-content-secondary">SPY, QQQ, VTI, ARKK, and 6+ more</td><td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-info-light text-info">TwelveData API</span></td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Indices</td><td class="py-2.5 px-3 text-content-secondary">S&P 500, NASDAQ, Dow Jones, FTSE 100, DAX, and 3+ more</td><td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-info-light text-info">TwelveData API</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </x-admin.card>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">How Trading Works</h3>
                        <ol class="space-y-2 text-sm text-content-secondary list-decimal list-inside">
                            <li>User opens the trading page — sees assets with live prices and price changes</li>
                            <li>Selects an asset (e.g., Bitcoin or EUR/USD)</li>
                            <li>Chooses direction — <strong class="text-success">Buy (Call)</strong> or <strong class="text-danger">Sell (Put)</strong></li>
                            <li>Sets trade amount, leverage (2x–100x), and expiry time</li>
                            <li>Trade opens — amount deducted from account balance</li>
                            <li>Trade closes at expiry — system determines WIN or LOSS</li>
                        </ol>
                    </x-admin.card>

                    <x-admin.card>
                        <div class="bg-primary/5 border border-primary/20 rounded-lg p-4">
                            <h3 class="text-base font-semibold text-primary mb-3">🔑 Admin-Controlled Win Rate</h3>
                            <p class="text-sm text-content-secondary mb-3">This is the key feature that gives you control over the platform's profitability:</p>
                            <ul class="space-y-1.5 text-sm text-content-secondary">
                                <li>• Every user has a <strong class="text-content">Win Rate (0–100%)</strong> that you set from the admin panel</li>
                                <li>• When a trade expires, the system generates a random number (1–100)</li>
                                <li>• If the number ≤ user's win rate → <strong class="text-success">WIN</strong></li>
                                <li>• If the number > user's win rate → <strong class="text-danger">LOSS</strong></li>
                            </ul>
                            <div class="mt-3 p-3 bg-surface-card rounded-lg">
                                <p class="text-xs text-content-muted"><strong>Example:</strong> Set a user's win rate to 40% → approximately 40% of their trades will win, 60% will lose. Adjust per-user at any time.</p>
                            </div>
                        </div>
                    </x-admin.card>
                </div>

                <x-admin.card>
                    <h3 class="text-base font-semibold text-content mb-3">User Trading Features</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @php $tradingFeatures = [
                            ['Open Positions', 'View active trades with live countdown timers'],
                            ['Trade History', 'Complete past trades with dates, amounts & outcomes'],
                            ['Request Early Close', 'Close a trade before expiry time'],
                            ['Trade Analytics', 'Win rate, total P&L, best/worst trade, breakdown by asset'],
                            ['Demo Trading', 'Practice with virtual $10,000 balance — zero risk'],
                            ['Portfolio Overview', 'Aggregated view of all trades, investments, holdings & net worth'],
                            ['Live Charts', 'Professional TradingView charts with indicators & drawing tools'],
                            ['Market News', 'Current market headlines and financial updates'],
                        ]; @endphp
                        @foreach($tradingFeatures as [$title, $desc])
                        <div class="bg-surface-alt rounded-lg p-3">
                            <div class="text-sm font-semibold text-content">{{ $title }}</div>
                            <div class="text-xs text-content-secondary mt-1">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>
                </x-admin.card>
            </div>
        </div>

        {{-- ===================== TAB: INVESTMENTS & COPY TRADING ===================== --}}
        <div x-show="activeTab === 'investments'" x-transition>
            <div class="space-y-6">
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                        Investment Plans
                    </h3>
                    <p class="text-sm text-content-secondary mb-4">Users invest a lump sum and receive automated returns over time — similar to a fixed deposit.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-surface-alt rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-content mb-2">How It Works:</h4>
                            <ol class="space-y-1.5 text-sm text-content-secondary list-decimal list-inside">
                                <li>Admin creates plans with name, price range, ROI%, duration, and return interval</li>
                                <li>Users browse and join a plan — amount deducted from balance</li>
                                <li>System auto-credits ROI at each interval (daily, weekly, monthly)</li>
                                <li>Plan matures — user gets final return (+ optionally original capital)</li>
                                <li>Users can cancel early for partial refund</li>
                            </ol>
                        </div>
                        <div class="bg-surface-alt rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-content mb-2">Admin Controls:</h4>
                            <ul class="space-y-1.5 text-sm text-content-secondary">
                                <li>• Create unlimited plan tiers with different terms</li>
                                <li>• Set min/max investment amounts per plan</li>
                                <li>• Configure ROI percentages and schedules</li>
                                <li>• Enable/disable capital return at maturity</li>
                                <li>• Approve/reject purchases and manage status</li>
                                <li>• View all active investments across the platform</li>
                            </ul>
                        </div>
                    </div>
                </x-admin.card>

                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        Copy Trading
                    </h3>
                    <p class="text-sm text-content-secondary mb-4">Users automatically mirror expert traders. They invest money, and the system generates daily profits.</p>
                    <div class="bg-surface-alt rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-semibold text-content mb-3">How It Works — Step by Step:</h4>
                        <ol class="space-y-2 text-sm text-content-secondary list-decimal list-inside">
                            <li><strong class="text-content">Admin creates expert profiles</strong> — Name, photo, bio, area of expertise, win rate, min capital, daily ROI%, duration, profit share%, max capital</li>
                            <li><strong class="text-content">Users browse experts</strong> — See statistics, ROI history, and follower count</li>
                            <li><strong class="text-content">User starts copying</strong> — Invests amount (must meet minimum), deducted from balance</li>
                            <li><strong class="text-content">System generates profits daily</strong> — Automated job calculates based on expert's win rate and daily ROI. Profit split between user and expert</li>
                            <li><strong class="text-content">Simulated trade history</strong> — ~180 realistic trades/day per position (60% wins, 40% losses)</li>
                            <li><strong class="text-content">Position matures</strong> — Auto-settled; profit (minus expert's share) + original investment credited to user</li>
                            <li><strong class="text-content">Early exit available</strong> — Users can stop copying anytime</li>
                        </ol>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Create/edit/delete experts', 'Toggle active/inactive', 'View all positions', 'Settle early', 'Stop positions', 'Adjust profit', 'Bulk settle'] as $action)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-surface-alt text-content-secondary border border-border">{{ $action }}</span>
                        @endforeach
                    </div>
                </x-admin.card>
            </div>
        </div>

        {{-- ===================== TAB: ALL MODULES ===================== --}}
        <div x-show="activeTab === 'modules'" x-transition>
            <div class="space-y-6">

                {{-- Pre-IPO --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 0h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg>
                        Pre-IPO Share Trading
                    </h3>
                    <p class="text-sm text-content-secondary mb-3">Users buy shares in pre-IPO companies at admin-controlled prices. When the company goes public, prices switch to live market data.</p>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-surface-alt text-content-secondary">Upcoming → Open → Closed → IPO → Public</span>
                    </div>
                    <p class="text-xs text-content-muted">Admin: Create companies, adjust prices (history tracked), manage lifecycle, view all holdings. Users: Browse companies, buy shares, view holdings with P&L, sell after IPO.</p>
                </x-admin.card>

                {{-- Stock Trading --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z M9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625z M16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        Stock Trading
                    </h3>
                    <p class="text-sm text-content-secondary">Users browse stocks with live prices, buy/sell shares, track portfolio with unrealized P&L, and view transaction history. Admin can view all positions, create manual positions (bonuses), and manage holdings.</p>
                </x-admin.card>

                {{-- Crypto Swap --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                        Crypto Swap
                    </h3>
                    <p class="text-sm text-content-secondary">Users exchange one cryptocurrency for another (e.g., BTC → ETH) with live exchange rates. Admin controls: enable/disable globally, set exchange fee %, view all transactions.</p>
                </x-admin.card>

                {{-- Loans --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                        Loan System
                    </h3>
                    <p class="text-sm text-content-secondary mb-3">Full-lifecycle lending: admin creates loan plans with APR, amounts, duration, fees. Users browse plans, use the repayment calculator, apply, and repay in installments.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Pending', 'Approved', 'Repaying', 'Completed', 'Rejected', 'Defaulted'] as $status)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $status === 'Completed' ? 'bg-success-light text-success' : ($status === 'Rejected' || $status === 'Defaulted' ? 'bg-danger-light text-danger' : 'bg-surface-alt text-content-secondary') }}">{{ $status }}</span>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-content-muted">Features: Amortization schedule, grace periods, late fees, overdue tracking, default management, repay via balance or deposit.</p>
                </x-admin.card>

                {{-- NFT Marketplace --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v13.5a1.5 1.5 0 001.5 1.5z" /></svg>
                        NFT Marketplace
                    </h3>
                    <p class="text-sm text-content-secondary mb-3">Full marketplace: users browse gallery, mint/create NFTs (gas fee), buy directly, place bids (admin-approved), sell/relist, like NFTs, organized by collections and categories.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                        @foreach(['Browse Gallery', 'Mint/Create', 'Buy Direct', 'Place Bids', 'Sell/Relist', 'Collections', 'Categories', 'Featured Items', 'Like Social', 'My NFTs'] as $feature)
                        <div class="bg-surface-alt rounded-lg p-2 text-center text-xs font-medium text-content-secondary">{{ $feature }}</div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-content-muted">Prices in ETH with auto USD conversion via CoinGecko. Admin: manage NFTs, approve user creations, approve bids, manage collections/categories, toggle featured, view sales.</p>
                </x-admin.card>

                {{-- Trading Signals --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        Trading Signals
                    </h3>
                    <p class="text-sm text-content-secondary">Expert-curated trade recommendations. Admin creates signal plans (subscription tiers). Users subscribe, receive signal alerts (asset, direction, entry, TP, SL), manage subscriptions, and renew.</p>
                </x-admin.card>

                {{-- Education --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                        Education & Courses
                    </h3>
                    <p class="text-sm text-content-secondary">Built-in LMS. Admin creates courses by category with video lessons. Users browse, purchase, and watch lessons. Admin can publish/unpublish, reorder lessons, and manage categories.</p>
                </x-admin.card>

                {{-- Tools --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25 3.5a.75.75 0 01-1.08-.83l1-5.82-4.26-3.69a.75.75 0 01.42-1.28l5.85-.5L10.45 1.72a.75.75 0 011.1 0l2.35 4.83 5.85.5a.75.75 0 01.42 1.28l-4.26 3.69 1 5.82a.75.75 0 01-1.08.83l-5.25-3.5z" /></svg>
                        Additional User Tools
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @php $tools = [
                            ['Live TradingView Charts', 'Real-time charts with indicators & drawings'],
                            ['Economic Calendar', 'Upcoming events impacting markets'],
                            ['Technical Analysis', 'Live analysis tools and indicators'],
                            ['Market News', 'Financial headlines and updates'],
                            ['Support Tickets', 'Create tickets, admin replies, conversation threads'],
                            ['Notification Center', 'In-app alerts with mark-read & delete'],
                        ]; @endphp
                        @foreach($tools as [$name, $desc])
                        <div class="bg-surface-alt rounded-lg p-3">
                            <div class="text-sm font-semibold text-content">{{ $name }}</div>
                            <div class="text-xs text-content-secondary mt-1">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>
                </x-admin.card>

            </div>
        </div>

        {{-- ===================== TAB: ADMIN PANEL ===================== --}}
        <div x-show="activeTab === 'admin'" x-transition>
            <div class="space-y-6">

                {{-- User Management --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        User Management (25+ Actions)
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @php $userActions = [
                            'View user details & wallets',
                            'Edit user profile',
                            'Create user accounts',
                            'Block/unblock users',
                            'Delete users',
                            '🔑 Set per-user win rate (0–100%)',
                            'Set trade mode (enable/disable)',
                            'Set signal strength',
                            'Set withdrawal/broker code',
                            'Reset user password',
                            'Manually verify email',
                            'Switch to user (see their view)',
                            'Send email to one user',
                            'Email all users (broadcast)',
                            'Send in-app notification',
                            'Add manual transactions (credit/debit)',
                            'Assign referral links',
                            'View login activity',
                            'Approve/manage investment plans',
                            'Clear account (full reset)',
                        ]; @endphp
                        @foreach($userActions as $action)
                        <div class="flex items-center gap-2 text-sm text-content-secondary py-1">
                            <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            <span>{!! $action !!}</span>
                        </div>
                        @endforeach
                    </div>
                </x-admin.card>

                {{-- Financial + Trade Management --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Deposit Management</h3>
                        <ul class="space-y-1.5 text-sm text-content-secondary">
                            <li>• View pending deposits waiting for approval</li>
                            <li>• Approve/reject deposits (one click)</li>
                            <li>• View uploaded payment proof images</li>
                            <li>• Edit deposit amounts before approval</li>
                            <li>• Delete deposit records</li>
                        </ul>
                    </x-admin.card>
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Withdrawal Management</h3>
                        <ul class="space-y-1.5 text-sm text-content-secondary">
                            <li>• View pending withdrawal requests</li>
                            <li>• Approve/reject with reason</li>
                            <li>• Process crypto payouts via Binance Pay</li>
                            <li>• Manual bank transfer processing</li>
                        </ul>
                    </x-admin.card>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Trade Management</h3>
                        <ul class="space-y-1.5 text-sm text-content-secondary">
                            <li>• View all trades with filters (user, asset, status, date)</li>
                            <li>• Create manual trades on behalf of users</li>
                            <li>• Edit trade details (amount, leverage, dates)</li>
                            <li>• Manually adjust profit/loss outcomes</li>
                            <li>• Settle trades individually or bulk settle</li>
                        </ul>
                    </x-admin.card>
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Content & CMS</h3>
                        <ul class="space-y-1.5 text-sm text-content-secondary">
                            <li>• Manage FAQs (displayed on homepage)</li>
                            <li>• Manage testimonials (homepage carousel)</li>
                            <li>• Upload/update homepage images</li>
                            <li>• Edit homepage text content</li>
                            <li>• Edit Privacy Policy & Terms pages</li>
                        </ul>
                    </x-admin.card>
                </div>

                {{-- Settings Overview --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">Settings & Configuration</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-surface-alt rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-content mb-1">App Settings</h4>
                            <p class="text-xs text-content-secondary">Site name, logo, favicon, description, contact info, currency</p>
                        </div>
                        <div class="bg-surface-alt rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-content mb-1">Security Toggles</h4>
                            <p class="text-xs text-content-secondary">Email verification, 2FA, KYC requirement, reCAPTCHA</p>
                        </div>
                        <div class="bg-surface-alt rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-content mb-1">Payment Settings</h4>
                            <p class="text-xs text-content-secondary">Deposit/withdrawal methods, bank details, crypto wallets, gateway keys</p>
                        </div>
                        <div class="bg-surface-alt rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-content mb-1">Fee Settings</h4>
                            <p class="text-xs text-content-secondary">Trading, withdrawal, swap fees, recurring account fees</p>
                        </div>
                        <div class="bg-surface-alt rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-content mb-1">Referral & Bonuses</h4>
                            <p class="text-xs text-content-secondary">5-level referral commissions, signup bonus, deposit bonus, loyalty</p>
                        </div>
                        <div class="bg-surface-alt rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-content mb-1">API Keys</h4>
                            <p class="text-xs text-content-secondary">CoinGecko, TwelveData, Binance Pay keys with test connection button</p>
                        </div>
                    </div>
                </x-admin.card>

                {{-- Module Toggles --}}
                <x-admin.card>
                    <h3 class="text-base font-semibold text-content mb-3">Module Toggles (Enable/Disable Entire Features)</h3>
                    <p class="text-sm text-content-secondary mb-3">Disabling a module hides it completely from users — no coding needed.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Trading', 'Investment Plans', 'Copy Trading', 'Loans', 'NFT Marketplace', 'Signals', 'Courses', 'Crypto Swap', 'Pre-IPO', 'Stock Trading', 'Google Translate', 'Announcements'] as $module)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-success-light text-success border border-success/20">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            {{ $module }}
                        </span>
                        @endforeach
                    </div>
                </x-admin.card>

                {{-- CRM --}}
                <x-admin.card>
                    <h3 class="text-base font-semibold text-content mb-3">CRM & Operations</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                        @foreach(['Tasks & Calendar', 'Customer Records', 'Lead Tracking', 'Lead Assignment', 'KYC Processing', 'Support Tickets'] as $crm)
                        <div class="bg-surface-alt rounded-lg p-2.5 text-center text-xs font-medium text-content-secondary">{{ $crm }}</div>
                        @endforeach
                    </div>
                </x-admin.card>

            </div>
        </div>

        {{-- ===================== TAB: INTEGRATIONS & AUTOMATION ===================== --}}
        <div x-show="activeTab === 'integrations'" x-transition>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Payment Gateways</h3>
                        <div class="space-y-2">
                            @php $gateways = [
                                ['Binance Pay', 'Crypto', 'USDT deposits & payouts'],
                                ['CoinPayments', 'Crypto', 'BTC, ETH, LTC, BUSD, USDT'],
                                ['Paystack', 'Fiat', 'Card payments (African markets)'],
                                ['Flutterwave', 'Fiat', 'Cards & mobile money (150+ currencies)'],
                            ]; @endphp
                            @foreach($gateways as [$name, $type, $desc])
                            <div class="flex items-center justify-between p-2.5 bg-surface-alt rounded-lg">
                                <div>
                                    <span class="text-sm font-medium text-content">{{ $name }}</span>
                                    <span class="text-xs text-content-muted ml-2">{{ $desc }}</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $type === 'Crypto' ? 'bg-warning-light text-warning' : 'bg-info-light text-info' }}">{{ $type }}</span>
                            </div>
                            @endforeach
                        </div>
                    </x-admin.card>

                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Market Data APIs</h3>
                        <div class="space-y-2">
                            <div class="p-2.5 bg-surface-alt rounded-lg">
                                <span class="text-sm font-medium text-content">CoinGecko</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-light text-success ml-2">Free tier</span>
                                <p class="text-xs text-content-secondary mt-1">35+ cryptocurrencies — price, volume, market cap, 24h change</p>
                            </div>
                            <div class="p-2.5 bg-surface-alt rounded-lg">
                                <span class="text-sm font-medium text-content">TwelveData</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-info-light text-info ml-2">Paid (affordable)</span>
                                <p class="text-xs text-content-secondary mt-1">44+ forex pairs, stocks, ETFs & indices — real-time quotes</p>
                            </div>
                        </div>
                    </x-admin.card>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">Communication & Security</h3>
                        <div class="space-y-2 text-sm text-content-secondary">
                            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg><span><strong class="text-content">Email:</strong> Mailgun / Amazon SES / Postmark</span></div>
                            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg><span><strong class="text-content">Live Chat:</strong> Tawk.to widget</span></div>
                            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg><span><strong class="text-content">Chatbot:</strong> BotMan (Telegram)</span></div>
                            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg><span><strong class="text-content">Bot Protection:</strong> Google reCAPTCHA</span></div>
                            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg><span><strong class="text-content">Social Login:</strong> Google, Facebook, Twitter, LinkedIn, GitHub, Bitbucket</span></div>
                        </div>
                    </x-admin.card>

                    <x-admin.card>
                        <h3 class="text-base font-semibold text-content mb-3">24 Email Notification Types</h3>
                        <div class="grid grid-cols-2 gap-1.5 text-xs text-content-secondary">
                            @foreach(['Welcome Email', 'Registration Confirm', '2FA Code', 'Deposit Status', 'Withdrawal Status', 'P2P Transfer', 'Trade Executed', 'Trade Result', 'Admin-Placed Trade', 'Signal Alert', 'ROI Payment', 'Plan Ended', 'Loan Request (→ admin)', 'Loan Approved', 'Loan Rejected', 'Repayment Confirmed', 'Payment Reminder', 'Loan Overdue', 'Loan Defaulted', 'Loan Completed', 'NFT Bid Approved', 'Pre-IPO Purchase', 'Pre-IPO Status Change', 'Custom Notification'] as $email)
                            <div class="flex items-center gap-1.5 py-0.5">
                                <svg class="w-3 h-3 text-success flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                {{ $email }}
                            </div>
                            @endforeach
                        </div>
                    </x-admin.card>
                </div>

                {{-- Background Automation --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Background Automation (Cron System)
                    </h3>
                    <p class="text-sm text-content-secondary mb-4">A single URL endpoint triggers all background tasks. Set your server to visit it every 1–5 minutes. No complex configuration needed.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead><tr class="border-b border-border">
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Task</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">What It Does</th>
                            </tr></thead>
                            <tbody class="divide-y divide-border">
                                <tr><td class="py-2.5 px-3 font-medium text-content">Process Trades</td><td class="py-2.5 px-3 text-content-secondary">Checks expired trades, determines win/loss, credits/debits accounts</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Update Crypto Prices</td><td class="py-2.5 px-3 text-content-secondary">Fetches fresh prices from CoinGecko for 35+ crypto assets</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Update Market Prices</td><td class="py-2.5 px-3 text-content-secondary">Fetches forex, stock, ETF & index prices from TwelveData</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Copy Trade Profits</td><td class="py-2.5 px-3 text-content-secondary">Distributes hourly P&L to all active copy trading positions</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Generate Simulated Trades</td><td class="py-2.5 px-3 text-content-secondary">Creates ~180 realistic trades/day per copy position (60% wins)</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Distribute ROI</td><td class="py-2.5 px-3 text-content-secondary">Credits investment plan returns (daily, weekly, or monthly)</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Loan Payment Checks</td><td class="py-2.5 px-3 text-content-secondary">Due dates, overdue tracking, late fee application</td></tr>
                                <tr><td class="py-2.5 px-3 font-medium text-content">Auto-Settle Positions</td><td class="py-2.5 px-3 text-content-secondary">Closes matured copy trading positions and credits users</td></tr>
                            </tbody>
                        </table>
                    </div>
                </x-admin.card>
            </div>
        </div>

        {{-- ===================== TAB: ENHANCED FEATURES ===================== --}}
        <div x-show="activeTab === 'enhanced'" x-transition>
            <div class="space-y-6">
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-2">Custom Improvements & New Modules</h3>
                    <p class="text-sm text-content-secondary mb-4">These features were either newly built or significantly enhanced beyond the original platform.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead><tr class="border-b border-border">
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Module</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">Type</th>
                                <th class="text-left py-2.5 px-3 text-xs font-semibold text-content-muted uppercase tracking-wider">What Was Improved / Added</th>
                            </tr></thead>
                            <tbody class="divide-y divide-border">
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Trading System</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Enhanced</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">85+ managed assets (was 52 hardcoded), live CoinGecko + TwelveData prices, per-user win rate (was global), demo trading, trade analytics</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Copy Trading</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Enhanced</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Expert bios & areas of expertise, simulated trade history (180/day), pro-rated hourly P&L, auto-settle on maturity, admin position management</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Loan System</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Enhanced</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Structured loan plans with APR, repayment calculator, amortization schedules, grace periods, late fees, overdue tracking, default management</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">NFT System</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Enhanced</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Collections, categories, featured items, bid approval workflow, admin gallery management</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Pre-IPO Module</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-light text-success">New</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Company listings, 5-stage status lifecycle, price history tracking, IPO conversion to live market data, user holdings with P&L</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Stock Trading</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-light text-success">New</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Buy/sell stocks, portfolio tracking, admin position management</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Trading Assets</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-light text-success">New</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Admin catalog with 85+ assets, toggle active/inactive, manual price override, one-click API refresh</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">User Dashboard</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Redesigned</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Complete migration from Bootstrap to dark + emerald Tailwind CSS theme</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Admin Panel</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Redesigned</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Migration from Bootstrap "Purpose" theme to professional teal + slate Tailwind CSS design</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Notifications</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-light text-success">New</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">In-app notification center with mark-read and delete, 24 email notification types (was basic)</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-medium text-content">Background Automation</td>
                                    <td class="py-2.5 px-3"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-light text-warning">Enhanced</span></td>
                                    <td class="py-2.5 px-3 text-content-secondary">Unified cron endpoint, simulated trade generation, copy trade P&L, loan payment checks</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-admin.card>

                {{-- Quick Start --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">
                        <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                        Quick Start Checklist for New Admins
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                        @php $steps = [
                            'Login to Admin Panel — visit your admin login URL',
                            'Update Site Info — Settings → App Settings (name, logo, contact)',
                            'Set Up API Keys — Settings → API Keys (CoinGecko, TwelveData)',
                            'Configure Payments — Settings → Payment Settings (wallets, gateways)',
                            'Create Investment Plans — Plans → New Plan',
                            'Add Expert Traders — Expert Management → Create Expert',
                            'Add Pre-IPO Companies — Pre-IPO → Create Company',
                            'Create Loan Plans — Loan Plans → Create',
                            'Add NFT Content — NFTs → Create',
                            'Create Courses — Courses → Add Course',
                            'Set Up Cron Job — server visits /allcron every 1–5 min',
                            'Update Homepage — Frontend Management (FAQs, testimonials)',
                            'Register a Test User — test from the user perspective',
                            'Set Win Rates — User Management → set win rates',
                            'Go Live! — share your URL and start onboarding',
                        ]; @endphp
                        @foreach($steps as $i => $step)
                        <div class="flex items-start gap-3 py-1.5">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-primary/10 text-primary text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                            <span class="text-sm text-content-secondary">{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </x-admin.card>
            </div>
        </div>

    </x-admin.tabs>

    {{-- Design & UI Section (always visible at bottom) --}}
    <x-admin.card>
        <h3 class="text-lg font-semibold text-content mb-4">
            <svg class="w-5 h-5 inline-block mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
            Design & User Interface
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-surface-alt rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-4 h-4 rounded-full" style="background: #0F1115;"></div>
                    <div class="w-4 h-4 rounded-full" style="background: #2E5C8A;"></div>
                    <h4 class="text-sm font-semibold text-content">User Dashboard</h4>
                </div>
                <p class="text-xs text-content-secondary">Dark + emerald green theme. Professional, Robinhood/Binance-inspired. Responsive, sidebar navigation, Inter font, smooth transitions, live price tickers.</p>
            </div>
            <div class="bg-surface-alt rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-4 h-4 rounded-full" style="background: #0F766E;"></div>
                    <div class="w-4 h-4 rounded-full" style="background: #475569;"></div>
                    <h4 class="text-sm font-semibold text-content">Admin Panel</h4>
                </div>
                <p class="text-xs text-content-secondary">Teal + slate theme. Data-focused, card-based layout. Responsive tables, modal dialogs, dark mode support. Clean and professional.</p>
            </div>
            <div class="bg-surface-alt rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-4 h-4 rounded-full" style="background: #0F1115;"></div>
                    <div class="w-4 h-4 rounded-full" style="background: #F5F7F9;"></div>
                    <h4 class="text-sm font-semibold text-content">Public Website</h4>
                </div>
                <p class="text-xs text-content-secondary">Dark header/footer matching dashboard. Light body sections for trust. Poppins + Merriweather fonts. Trust indicators: live deposit feeds, user count.</p>
            </div>
        </div>
    </x-admin.card>

    {{-- Technology --}}
    <x-admin.card padding="p-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="text-sm text-content-secondary">
                <strong class="text-content">Built with:</strong> Laravel 8.x • MySQL • Tailwind CSS • Alpine.js 3 • TradingView Charts — Runs on standard web hosting (Apache/PHP/MySQL)
            </div>
            <div class="flex items-center gap-2 text-xs text-content-muted">
                <span>TradexPro Platform</span>
                <span>•</span>
                <span>All features manageable from admin panel — no coding required</span>
            </div>
        </div>
    </x-admin.card>

</div>

@endsection

