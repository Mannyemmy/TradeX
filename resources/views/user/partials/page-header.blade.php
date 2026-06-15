{{--
    Page Header Partial
    Usage: @include('user.partials.page-header', ['title' => 'Dashboard', 'subtitle' => 'Welcome back'])
--}}
@php $title = $title ?? 'Dashboard'; $subtitle = $subtitle ?? ''; @endphp

<div class="mb-6">
    <h2 class="text-xl font-bold text-content-primary">{{ $title }}</h2>
    @if ($subtitle)
        <p class="text-sm text-content-secondary mt-1">{{ $subtitle }}</p>
    @endif
</div>
