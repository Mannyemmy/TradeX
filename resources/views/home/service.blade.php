@extends('layouts.base')

@section('title', 'Careers')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text leading-tight">
            Work with us at <span class="text-primary">{{ $settings->site_name }}</span>
        </h1>
        <p class="text-body-muted text-lg mt-3">We are always looking for people who are passionate about financial technology and want to help build a better trading platform.</p>
        <p class="text-body-muted mt-2">If you are interested in joining our team, check the open positions below or send your CV to <a href="mailto:{{ $settings->contact_email }}" class="text-primary hover:underline">{{ $settings->contact_email }}</a>.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            @php
                $pillars = [
                    ['title' => 'Growth', 'desc' => 'We invest in our team. You will have access to learning resources and the opportunity to develop new skills as the platform grows.'],
                    ['title' => 'Collaboration', 'desc' => 'We work as a team. Everyone has a voice, and we encourage open communication and knowledge sharing across departments.'],
                    ['title' => 'Flexibility', 'desc' => 'We offer remote and hybrid work arrangements where possible, so you can do your best work from wherever you are.'],
                ];
            @endphp
            @foreach($pillars as $p)
            <div class="border-l-4 border-primary pl-5">
                <h4 class="font-semibold text-body-text">{{ $p['title'] }}</h4>
                <p class="text-body-muted text-sm mt-2 leading-relaxed">{{ $p['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== OPEN POSITIONS ===== --}}
<section class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <h2 class="font-serif text-2xl font-bold text-body-text text-center mb-4">Open positions</h2>
        <p class="text-body-muted text-center mb-8">Don't see a role that fits? Send your CV to <a href="mailto:{{ $settings->contact_email }}" class="text-primary hover:underline">{{ $settings->contact_email }}</a> and we will keep it on file.</p>
        <div class="bg-body-bg rounded-xl border border-body-border p-8 text-center">
            <svg class="w-12 h-12 text-body-muted mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <p class="text-body-muted">No open positions at the moment. Check back later or email us your CV.</p>
        </div>
    </div>
</section>

{{-- ===== LEARNING RESOURCES ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8">
            <h2 class="font-serif text-2xl font-bold text-body-text">Learning resources</h2>
            <p class="text-body-muted text-lg mt-1">New to trading? Start here.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8">
                <div class="rounded-xl overflow-hidden border border-body-border shadow-sm">
                    <img src="{{ asset('temp/frontpage/img/in-cirro-18-video.jpg') }}" alt="Learning resources" class="w-full" />
                </div>
            </div>
            <div class="lg:col-span-4">
                <h3 class="font-semibold text-body-text text-lg">Topics covered</h3>
                <ul class="mt-4 space-y-3">
                    @php
                        $topics = ['Understanding market basics', 'Reading charts and price action', 'Managing risk and position sizing', 'Setting stop-loss and take-profit', 'Choosing the right asset class', 'Developing a trading plan'];
                    @endphp
                    @foreach($topics as $topic)
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-body-muted text-sm ml-2">{{ $topic }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('trading') }}" class="inline-flex items-center text-primary text-sm font-medium mt-4 hover:underline">
                    Go to Web Trader
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
@include('home.partials.cta-banner', [
    'title' => 'Interested in joining our team?',
    'subtitle' => 'Send your CV to ' . $settings->contact_email . ' and we\'ll be in touch.',
    'buttonText' => 'Contact Us',
    'buttonRoute' => 'contact',
])

@endsection
