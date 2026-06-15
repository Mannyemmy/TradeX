@extends('layouts.base')

@section('title', 'Contact Us')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text">Get in Touch</h1>
            <p class="text-body-muted text-lg mt-3">Have a question? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- LEFT: Info Cards --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-body-border p-6">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center flex-shrink-0 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-body-text">Address</h4>
                    </div>
                    <p class="text-body-muted text-sm">{{ $settings->office ?? 'Our Office Address' }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-body-border p-6">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center flex-shrink-0 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="font-semibold text-body-text">Email</h4>
                    </div>
                    <a href="mailto:{{ $settings->contact_email }}" class="text-primary hover:underline text-sm">{{ $settings->contact_email }}</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-body-border p-6">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center flex-shrink-0 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-body-text">Working Hours</h4>
                    </div>
                    <p class="text-body-muted text-sm">Monday — Friday: 9:00 AM — 5:00 PM<br>Weekend: Closed</p>
                </div>
            </div>

            {{-- RIGHT: Contact Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-body-border p-6 md:p-8">
                    <h3 class="font-semibold text-lg text-body-text mb-6">Send us a message</h3>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-6 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('enquiry') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-body-text mb-1">Your Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text placeholder-body-muted focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition" placeholder="John Doe" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-body-text mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text placeholder-body-muted focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition" placeholder="you@email.com" />
                            </div>
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-body-text mb-1">Subject <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text placeholder-body-muted focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition" placeholder="How can we help?" />
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-body-text mb-1">Message <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required class="w-full rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text placeholder-body-muted focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition resize-y" placeholder="Your message...">{{ old('message') }}</textarea>
                        </div>
                        @if($settings->captcha_status ?? false)
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-body-text mb-1">Captcha <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <img src="{{ captcha_src() }}" alt="captcha" class="rounded" />
                                <input type="text" name="captcha" required class="w-32 rounded-lg border border-body-border bg-body-bg px-4 py-2.5 text-sm text-body-text focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition" placeholder="Enter code" />
                            </div>
                        </div>
                        @endif
                        <div class="mt-6">
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-8 py-3 text-sm transition">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== MAP ===== --}}
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="rounded-xl overflow-hidden border border-body-border shadow-sm">
            <iframe src="https://maps.google.com/maps?q={{ urlencode($settings->office ?? '') }}&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
@include('home.partials.cta-banner', [
    'title' => 'Ready to open an account?',
    'subtitle' => 'Create your ' . $settings->site_name . ' account and start exploring the markets.',
    'buttonText' => 'Open Account',
    'buttonRoute' => 'register',
])

@endsection
