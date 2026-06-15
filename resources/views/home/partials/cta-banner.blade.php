{{-- Reusable emerald CTA banner --}}
{{-- Usage: @include('home.partials.cta-banner', ['title' => '...', 'subtitle' => '...', 'buttonText' => '...', 'buttonRoute' => 'register']) --}}
<section class="bg-primary py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-white">{{ $title }}</h2>
        @isset($subtitle)
        <p class="text-white/80 mt-3 text-lg">{{ $subtitle }}</p>
        @endisset
        <a href="{{ route($buttonRoute ?? 'register') }}"
           class="inline-block mt-6 bg-white text-primary font-semibold rounded-lg px-8 py-3 hover:bg-gray-100 transition shadow-lg">
            {{ $buttonText ?? 'Get Started' }}
        </a>
    </div>
</section>
