@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'Customer Support', 'subtitle' => 'We\'re here to help — send us a message'])

    <div class="max-w-2xl mx-auto">
        {{-- Support Card --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border p-6 mb-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary/10 mb-4">
                    <x-icon name="information-circle" class="w-7 h-7 text-primary" />
                </div>
                <h3 class="text-xl font-bold text-content-primary">{{ $settings->site_name }} Support</h3>
                <p class="text-sm text-content-secondary mt-2">For inquiries, suggestions or complaints, mail us at</p>
                <a href="mailto:{{ $settings->contact_email }}" class="text-primary hover:text-primary-light text-sm font-medium transition-colors">
                    {{ $settings->contact_email }}
                </a>
            </div>

            {{-- Contact Form --}}
            <form method="post" action="{{ route('enquiry') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="name" value="{{ Auth::user()->name }}" />
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                <div>
                    <label class="block text-sm font-medium text-content-primary mb-1.5">
                        Message <span class="text-loss">*</span>
                    </label>
                    <textarea name="message" rows="5" required
                              class="w-full px-4 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors"
                              placeholder="Type your message here..."></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    Send Message
                </button>
            </form>
        </div>
    </div>

@endsection
