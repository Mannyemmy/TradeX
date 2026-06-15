<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo e($settings->site_name); ?> - <?php echo $__env->yieldContent('title'); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e($settings->site_name); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset('storage/app/public/' . $settings->favicon)); ?>" sizes="any">

    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
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
                    gain: '<?php echo e($themeColors->gain_color ?? '#22C55E'); ?>',
                    loss: '<?php echo e($themeColors->loss_color ?? '#EF4444'); ?>',
                    warning: '<?php echo e($themeColors->warning_color ?? '#F59E0B'); ?>',
                    info: '<?php echo e($themeColors->info_color ?? '#3B82F6'); ?>',
                },
            },
        },
    }
    </script>
    <style type="text/tailwindcss">
    @layer  base {
        :root {
            --color-surface-base: <?php echo e($themeColors->surface_base ?? '#0F1115'); ?>;
            --color-surface-raised: <?php echo e($themeColors->surface_raised ?? '#161A1E'); ?>;
            --color-surface-overlay: <?php echo e($themeColors->surface_overlay ?? '#1C2127'); ?>;
            --color-surface-border: <?php echo e($themeColors->surface_border ?? '#2A2F36'); ?>;
            --color-surface-border-light: <?php echo e($themeColors->surface_border_light ?? '#363C44'); ?>;
            --color-content-primary: <?php echo e($themeColors->content_primary ?? '#E8EAED'); ?>;
            --color-content-secondary: <?php echo e($themeColors->content_secondary ?? '#9AA0AB'); ?>;
            --color-content-tertiary: <?php echo e($themeColors->content_tertiary ?? '#6B7280'); ?>;
        }
        html { background-color: <?php echo e($themeColors->surface_base ?? '#0F1115'); ?>; }
        body { font-family: 'Inter', system-ui, sans-serif; color: <?php echo e($themeColors->content_secondary ?? '#9AA0AB'); ?>; -webkit-font-smoothing: antialiased; }
        /* Custom select arrow for dark theme */
        select { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; -webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 2.5rem; }
    }
    </style>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body class="bg-surface-base min-h-screen flex flex-col">

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Language Selector -->
    <?php echo $__env->make('layouts.lang', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/layouts/guest1.blade.php ENDPATH**/ ?>