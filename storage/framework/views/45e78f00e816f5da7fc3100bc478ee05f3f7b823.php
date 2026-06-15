<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo e($settings->site_name); ?> - Unlock the Power of Your Finance" />
    <meta property="og:image" content="<?php echo e(asset('storage/app/public/' . $settings->logo)); ?>" />
    <meta property="og:url" content="<?php echo e($settings->site_address); ?>">
    <meta name="theme-color" content="<?php echo e($themeColors->primary_color ?? '#059669'); ?>" />
    <link rel="shortcut icon" href="<?php echo e(asset('storage/app/public/' . $settings->favicon)); ?>" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" href="<?php echo e(asset('storage/app/public/' . $settings->favicon)); ?>" />

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Poppins', 'system-ui', 'sans-serif'],
                    serif: ['Merriweather', 'Georgia', 'serif'],
                },
                colors: {
                    surface: {
                        base: '<?php echo e($themeColors->surface_base ?? '#0F1115'); ?>',
                        raised: '<?php echo e($themeColors->surface_raised ?? '#161A1E'); ?>',
                        overlay: '<?php echo e($themeColors->surface_overlay ?? '#1C2127'); ?>',
                        border: '<?php echo e($themeColors->surface_border ?? '#2A2F36'); ?>',
                        'border-light': '<?php echo e($themeColors->surface_border_light ?? '#363C44'); ?>',
                    },
                    content: {
                        primary: '<?php echo e($themeColors->content_primary ?? '#E8EAED'); ?>',
                        secondary: '<?php echo e($themeColors->content_secondary ?? '#9AA0AB'); ?>',
                        tertiary: '<?php echo e($themeColors->content_tertiary ?? '#6B7280'); ?>',
                        inverse: '<?php echo e($themeColors->surface_base ?? '#0F1115'); ?>',
                    },
                    primary: {
                        DEFAULT: '<?php echo e($themeColors->primary_color ?? '#059669'); ?>',
                        light: '<?php echo e($themeColors->primary_light ?? '#34D399'); ?>',
                        dark: '<?php echo e($themeColors->primary_dark ?? '#047857'); ?>',
                        subtle: '<?php echo \App\Models\ThemeColor::hexToRgba($themeColors->primary_color ?? '#059669', 0.12); ?>',
                    },
                    body: {
                        bg: '<?php echo e($themeColors->body_bg ?? '#F5F7F9'); ?>',
                        text: '<?php echo e($themeColors->body_text ?? '#1F2937'); ?>',
                        muted: '<?php echo e($themeColors->body_muted ?? '#6B7280'); ?>',
                        border: '<?php echo e($themeColors->body_border ?? '#E5E7EB'); ?>',
                    },
                    gain: '<?php echo e($themeColors->gain_color ?? '#22C55E'); ?>',
                    loss: '<?php echo e($themeColors->loss_color ?? '#EF4444'); ?>',
                    warning: '<?php echo e($themeColors->warning_color ?? '#F59E0B'); ?>',
                    info: '<?php echo e($themeColors->info_color ?? '#3B82F6'); ?>',
                },
            },
        },
    }
    </script>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <title><?php echo e($settings->site_name); ?> | <?php echo $__env->yieldContent('title', 'Home'); ?></title>

    
    <style>
        iframe.goog-te-banner-frame, iframe.skiptranslate { display: none !important; }
        body { position: static !important; top: 0px !important; }
        [x-cloak] { display: none !important; }
    </style>

    <?php echo $__env->yieldPushContent('head'); ?>
</head>

<body class="font-sans antialiased bg-body-bg text-body-text">
    
    <div id="page-loader" class="fixed inset-0 z-[9999] bg-white flex items-center justify-center">
        <div class="flex space-x-1.5">
            <div class="w-2.5 h-2.5 bg-primary rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2.5 h-2.5 bg-primary rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2.5 h-2.5 bg-primary rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
    </div>

    
    <?php echo $__env->make('home.partials.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('home.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div x-data="{ show: false }" @scroll.window="show = window.scrollY > 400" class="hidden md:block">
        <button x-show="show" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-transition
            class="fixed bottom-6 right-6 z-40 bg-primary hover:bg-primary-dark text-white rounded-full p-3 shadow-lg transition"
            aria-label="Back to top">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
        </button>
    </div>

    
    <script src="<?php echo e(asset('temp/frontpage/js/vendors/tradingview-widget.min.js')); ?>"></script>

    
    <script>
        setTimeout(function() {
            var loader = document.getElementById('page-loader');
            if (loader) loader.style.display = 'none';
        }, 800);
    </script>

    
    <?php echo $__env->make('layouts.lang', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('layouts.livechat', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/layouts/base.blade.php ENDPATH**/ ?>