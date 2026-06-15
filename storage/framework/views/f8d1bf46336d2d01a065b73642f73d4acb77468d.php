<?php
use App\Models\Wdmethod;
$dmethods = $paymethod = collect(); // lazy-loaded below only when deposit modals are needed
$showDepositModals = request()->is('dashboard') || request()->is('dashboard/deposits*') || request()->is('dashboard/payment*');
if ($showDepositModals) {
    $dmethods = $paymethod = Wdmethod::where(function ($query) {
        $query->where('type', '=', 'deposit')
            ->orWhere('type', '=', 'both');
    })->where('status', 'enabled')->orderByDesc('id')->get();
}
$unreadNotifCount = Auth::user()->unreadNotifications()->count();
$unreadTicketCount = \App\Models\SupportTicket::where('user_id', Auth::id())->where('status', 'answered')->count();
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — <?php echo e($settings->site_name); ?></title>
    <link rel="icon" href="<?php echo e(asset('storage/app/public/photos/'.$settings->favicon)); ?>" type="image/png">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    
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
                    info: '#3B82F6',
                },
            },
        },
    }
    </script>
    <style type="text/tailwindcss">
    @layer  base {
        [x-cloak] { display: none !important; }
        html { background-color: #0F1115; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #9AA0AB; -webkit-font-smoothing: antialiased; }
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0F1115; }
        ::-webkit-scrollbar-thumb { background: #2A2F36; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #363C44; }
    }
    @layer  components {
        .nav-link-active {
            @apply  bg-primary-subtle text-primary-light border-l-2 border-primary;
        }
        .nav-link-item {
            @apply  flex items-center gap-3 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors duration-150 border-l-2 border-transparent;
        }
        .nav-group-label {
            @apply  px-4 pt-5 pb-2 text-xs font-semibold uppercase tracking-wider text-content-tertiary;
        }
    }
    </style>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <?php echo \Livewire\Livewire::styles(); ?>

</head>

<body class="bg-surface-base font-sans text-content-secondary antialiased min-h-screen"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          mobileSidebar: false,
          userDropdown: false,
          notifDropdown: false,
      }"
      @resize.window="sidebarOpen = window.innerWidth >= 1024; if(window.innerWidth >= 1024) mobileSidebar = false"
>
    
    <div x-show="mobileSidebar" x-transition.opacity class="fixed inset-0 bg-black/60 z-40 lg:hidden" @click="mobileSidebar = false"></div>

    
    <aside
        :class="mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 z-50 h-full w-64 bg-surface-raised border-r border-surface-border flex flex-col transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0"
    >
        
        <div class="flex items-center justify-between h-16 px-4 border-b border-surface-border shrink-0">
            <a href="<?php echo e(url('dashboard')); ?>" class="flex items-center">
                <img src="<?php echo e(asset('storage/app/public/' . $settings->logo)); ?>" alt="<?php echo e($settings->site_name); ?>" class="h-8 w-auto max-w-[140px] object-contain">
            </a>
            <button @click="mobileSidebar = false" class="lg:hidden text-content-tertiary hover:text-content-primary">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'x-mark','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </button>
        </div>

        
        <div class="px-4 py-4 border-b border-surface-border shrink-0">
            <div class="flex items-center gap-3">
                <?php if(Auth::user()->profile_photo_path): ?>
                    <img src="<?php echo e(asset('storage/app/public/photos/' . Auth::user()->profile_photo_path)); ?>"
                         alt="<?php echo e(Auth::user()->name); ?>"
                         class="w-10 h-10 rounded-full object-cover bg-surface-overlay shrink-0">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center shrink-0">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'user-circle','class' => 'w-8 h-8 text-content-tertiary']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'user-circle','class' => 'w-8 h-8 text-content-tertiary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-content-primary truncate"><?php echo e(Auth::user()->name); ?></p>
                    <?php if($settings->enable_kyc == 'yes'): ?>
                    <p class="text-xs text-content-tertiary">
                        <?php echo e(Auth::user()->account_verify == 'Verified' ? '✓ Verified' : 'Unverified'); ?>

                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <nav class="flex-1 overflow-y-auto py-2">
            
            <p class="nav-group-label">Overview</p>
            <a href="<?php echo e(url('dashboard')); ?>" class="nav-link-item <?php echo e(request()->is('dashboard') && !request()->is('dashboard/*') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'home','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'home','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Dashboard
            </a>
            <a href="<?php echo e(route('user.trades.portfolio')); ?>" class="nav-link-item <?php echo e(request()->routeIs('user.trades.portfolio') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'briefcase','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'briefcase','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Portfolio
            </a>

            
            <p class="nav-group-label">Trading</p>
            <?php if(!empty($mod['trading'])): ?>
            <a href="<?php echo e(route('trade')); ?>" class="nav-link-item <?php echo e(request()->routeIs('trade') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'chart-bar','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'chart-bar','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Open Trade
            </a>
            <a href="<?php echo e(route('user.trades.markets')); ?>" class="nav-link-item <?php echo e(request()->routeIs('user.trades.markets') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'globe-alt','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'globe-alt','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Markets
            </a>
            <?php endif; ?>
            <?php if(!empty($mod['copy_trading'])): ?>
            <a href="<?php echo e(route('copyTrading')); ?>" class="nav-link-item <?php echo e(request()->routeIs('copyTrading*') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'copy','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'copy','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Copy Trading
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('tradinghistory')); ?>" class="nav-link-item <?php echo e(request()->routeIs('tradinghistory') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Trade History
            </a>

            
            <p class="nav-group-label">Wallet</p>
            <a href="<?php echo e(url('dashboard/deposits')); ?>" class="nav-link-item <?php echo e(request()->is('dashboard/deposits*') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'arrow-down-tray','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'arrow-down-tray','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Deposits
            </a>
            <?php if(!empty($mod['investment']) || !empty($mod['cryptoswap'])): ?>
            <a href="<?php echo e(route('withdrawalsdeposits')); ?>" class="nav-link-item <?php echo e(request()->routeIs('withdrawalsdeposits') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'arrow-up-tray','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'arrow-up-tray','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Withdrawals
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('accounthistory')); ?>" class="nav-link-item <?php echo e(request()->routeIs('accounthistory') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'document-text','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'document-text','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Transactions
            </a>
            <?php if(!empty($mod['loan'])): ?>
            <a href="<?php echo e(route('loans.create')); ?>" class="nav-link-item <?php echo e(request()->routeIs('loans.create') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'hand-raised','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'hand-raised','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Loans
            </a>
            <?php endif; ?>

            
            <p class="nav-group-label">Investments</p>
            <?php if(!empty($mod['investment'])): ?>
            <a href="<?php echo e(route('mplans')); ?>" class="nav-link-item <?php echo e(request()->routeIs('mplans') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'banknotes','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'banknotes','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Investment Plans
            </a>
            <?php endif; ?>
            <?php if(!empty($mod['pre_ipo'])): ?>
            <a href="<?php echo e(route('user.pre-ipo.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('user.pre-ipo.*') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'building-office','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'building-office','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Pre-IPO
            </a>
            <?php endif; ?>
            <?php if(!empty($mod['stocktrading'])): ?>
            <a href="<?php echo e(route('user.stocks.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('user.stocks.*') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'chart-bar','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'chart-bar','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Stock Shares
            </a>
            <?php endif; ?>
            <?php if(!empty($mod['signal'])): ?>
            <div x-data="{ open: <?php echo e(request()->routeIs('user.signal.*') || request()->routeIs('tsignals') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full nav-link-item flex items-center justify-between <?php echo e(request()->routeIs('user.signal.*') ? 'nav-link-active' : ''); ?>">
                    <span class="flex items-center gap-3"><?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'signal','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'signal','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Trading Signals</span>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open" x-transition x-cloak class="ml-8 space-y-0.5 mt-0.5">
                    <a href="<?php echo e(route('user.signal.index')); ?>" class="block py-1.5 px-3 text-sm rounded-lg <?php echo e(request()->routeIs('user.signal.index') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary'); ?> transition-colors">Signals</a>
                    <a href="<?php echo e(route('user.signal.plans')); ?>" class="block py-1.5 px-3 text-sm rounded-lg <?php echo e(request()->routeIs('user.signal.plans') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary'); ?> transition-colors">Signal Plans</a>
                    <a href="<?php echo e(route('user.signal.subscriptions')); ?>" class="block py-1.5 px-3 text-sm rounded-lg <?php echo e(request()->routeIs('user.signal.subscriptions') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary'); ?> transition-colors">My Subscriptions</a>
                </div>
            </div>
            <?php endif; ?>
            <?php if(!empty($mod['nft'])): ?>
            <div x-data="{ open: <?php echo e(request()->routeIs('nft.gallery', 'user.nfts.*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full nav-link-item flex items-center justify-between <?php echo e(request()->routeIs('nft.gallery', 'user.nfts.*') ? 'nav-link-active' : ''); ?>">
                    <span class="flex items-center gap-3"><?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'gem','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'gem','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> NFT Market</span>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open" x-transition x-cloak class="ml-8 space-y-0.5 mt-0.5">
                    <a href="<?php echo e(route('nft.gallery')); ?>" class="block py-1.5 px-3 text-sm rounded-lg <?php echo e(request()->routeIs('nft.gallery') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary'); ?> transition-colors">Gallery</a>
                    <a href="<?php echo e(route('user.nfts.my')); ?>" class="block py-1.5 px-3 text-sm rounded-lg <?php echo e(request()->routeIs('user.nfts.my') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary'); ?> transition-colors">My Collection</a>
                    <a href="<?php echo e(route('user.nfts.create')); ?>" class="block py-1.5 px-3 text-sm rounded-lg <?php echo e(request()->routeIs('user.nfts.create') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary'); ?> transition-colors">Mint NFT</a>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(!empty($mod['membership'])): ?>
            <p class="nav-group-label">Education</p>
            <a href="<?php echo e(route('user.courses')); ?>" class="nav-link-item <?php echo e(request()->routeIs('user.courses') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'academic-cap','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'academic-cap','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Courses
            </a>
            <a href="<?php echo e(route('user.mycourses')); ?>" class="nav-link-item <?php echo e(request()->routeIs('user.mycourses', 'user.mycoursedetails', 'user.learning') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'book-open','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'book-open','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> My Courses
            </a>
            <?php endif; ?>

            
            <p class="nav-group-label">Account</p>
            <a href="<?php echo e(route('profile')); ?>" class="nav-link-item <?php echo e(request()->routeIs('profile') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'cog','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'cog','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Profile & Settings
            </a>
            <?php if($settings->enable_kyc == 'yes' && Auth::user()->account_verify != 'Verified'): ?>
            <a href="<?php echo e(route('account.verify')); ?>" class="nav-link-item <?php echo e(request()->routeIs('account.verify') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'shield-check','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Verification
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('referuser')); ?>" class="nav-link-item <?php echo e(request()->routeIs('referuser') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'users','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'users','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Referral Program
            </a>
            <a href="<?php echo e(url('dashboard/news')); ?>" class="nav-link-item <?php echo e(request()->is('dashboard/news') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'newspaper','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'newspaper','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Market News
            </a>
            <a href="<?php echo e(route('support')); ?>" class="nav-link-item <?php echo e(request()->routeIs('support') || request()->routeIs('support.*') ? 'nav-link-active' : ''); ?>">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'chat-bubble-left-right','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'chat-bubble-left-right','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <span class="flex-1">Support</span>
                <?php if($unreadTicketCount > 0): ?>
                    <span class="min-w-[18px] h-[18px] flex items-center justify-center bg-loss text-white text-[10px] font-bold rounded-full px-1"><?php echo e($unreadTicketCount > 99 ? '99+' : $unreadTicketCount); ?></span>
                <?php endif; ?>
            </a>
        </nav>

        
        <div class="border-t border-surface-border p-4 shrink-0">
            <form method="POST" action="<?php echo e(route('logout')); ?>" id="sidebar-logout-form">
                <?php echo csrf_field(); ?>
            </form>
            <a href="<?php echo e(route('logout')); ?>"
               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
               class="nav-link-item !px-0 text-loss/80 hover:text-loss">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'arrow-right-on-rectangle','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'arrow-right-on-rectangle','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Logout
            </a>
        </div>
    </aside>

    
    <header class="fixed top-0 right-0 z-30 h-16 bg-surface-raised border-b border-surface-border transition-all duration-200 lg:left-64 left-0">
        <div class="flex items-center justify-between h-full px-4 lg:px-6">
            
            <div class="flex items-center gap-3">
                <button @click="mobileSidebar = !mobileSidebar" class="lg:hidden text-content-tertiary hover:text-content-primary p-1">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'bars-3','class' => 'w-6 h-6']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'bars-3','class' => 'w-6 h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </button>
                <h1 class="text-lg font-semibold text-content-primary hidden sm:block"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
            </div>

            
            <div class="flex items-center gap-2">
                
                <div class="relative"
                     x-data="{
                         notifs: [],
                         count: <?php echo e($unreadNotifCount); ?>,
                         loaded: false,
                         loadNotifs() {
                             if (this.loaded) return;
                             fetch('<?php echo e(route("notifications.unread")); ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                 .then(r => r.json())
                                 .then(d => { this.notifs = d.notifications; this.count = d.count; this.loaded = true; });
                         },
                         markAllRead() {
                             fetch('<?php echo e(route("notifications.readAll")); ?>', { method: 'POST', headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' } })
                                 .then(() => { this.notifs = []; this.count = 0; });
                         }
                     }"
                     @click.away="notifDropdown = false">
                    <button @click="notifDropdown = !notifDropdown; loadNotifs()" class="relative p-2 text-content-tertiary hover:text-content-primary rounded-lg hover:bg-surface-overlay transition-colors">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'bell','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        <span x-show="count > 0" x-cloak class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center bg-loss text-white text-[10px] font-bold rounded-full px-1" x-text="count > 99 ? '99+' : count"></span>
                    </button>
                    <div x-show="notifDropdown" x-cloak x-transition
                         class="absolute right-0 mt-2 w-80 bg-surface-raised border border-surface-border rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-surface-border">
                            <h4 class="text-sm font-semibold text-content-primary">Notifications</h4>
                            <button x-show="count > 0" @click="markAllRead()" class="text-xs text-primary hover:text-primary-light transition-colors">Mark all read</button>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-surface-border">
                            <template x-if="notifs.length === 0">
                                <p class="text-sm text-content-tertiary text-center py-6">No new notifications</p>
                            </template>
                            <template x-for="n in notifs" :key="n.id">
                                <a :href="n.action_url || '#'" class="flex items-start gap-3 px-4 py-3 hover:bg-surface-overlay transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-primary-subtle flex items-center justify-center shrink-0 mt-0.5">
                                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'bell','class' => 'w-4 h-4 text-primary']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-4 h-4 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-content-primary truncate" x-text="n.title"></p>
                                        <p class="text-xs text-content-tertiary mt-0.5 line-clamp-2" x-text="n.message"></p>
                                        <p class="text-[10px] text-content-tertiary mt-1" x-text="n.time"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <a href="<?php echo e(route('notification')); ?>" class="block text-center text-xs text-primary hover:text-primary-light py-3 border-t border-surface-border transition-colors">View all notifications</a>
                    </div>
                </div>

                
                <?php if($settings->enable_kyc == 'yes'): ?>
                    <?php if(Auth::user()->account_verify == 'Verified'): ?>
                        <span class="hidden sm:inline-flex items-center gap-1 text-xs font-medium text-gain bg-gain/10 px-2.5 py-1 rounded-full">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'check-circle','class' => 'w-3.5 h-3.5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'w-3.5 h-3.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Verified
                        </span>
                    <?php else: ?>
                        <a href="<?php echo e(route('account.verify')); ?>" class="hidden sm:inline-flex items-center gap-1 text-xs font-medium text-warning bg-warning/10 px-2.5 py-1 rounded-full hover:bg-warning/20 transition-colors">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'shield-check','class' => 'w-3.5 h-3.5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-3.5 h-3.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Verify KYC
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                
                <div class="relative" @click.away="userDropdown = false">
                    <button @click="userDropdown = !userDropdown" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-overlay transition-colors">
                        <?php if(Auth::user()->profile_photo_path): ?>
                            <img src="<?php echo e(asset('storage/app/public/photos/' . Auth::user()->profile_photo_path)); ?>"
                                 alt="<?php echo e(Auth::user()->name); ?>"
                                 class="w-8 h-8 rounded-full object-cover bg-surface-overlay">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center">
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'user-circle','class' => 'w-6 h-6 text-content-tertiary']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'user-circle','class' => 'w-6 h-6 text-content-tertiary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <span class="text-sm font-medium text-content-primary hidden md:block"><?php echo e(Auth::user()->name); ?></span>
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'chevron-down','class' => 'w-4 h-4 text-content-tertiary hidden md:block']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'w-4 h-4 text-content-tertiary hidden md:block']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </button>
                    <div x-show="userDropdown" x-cloak x-transition
                         class="absolute right-0 mt-2 w-48 bg-surface-raised border border-surface-border rounded-xl shadow-xl py-1 z-50">
                        <a href="<?php echo e(route('profile')); ?>" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'user-circle','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'user-circle','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Profile
                        </a>
                        <a href="<?php echo e(url('dashboard/deposits')); ?>" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'arrow-down-tray','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'arrow-down-tray','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Deposit
                        </a>
                        <a href="<?php echo e(route('withdrawalsdeposits')); ?>" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'arrow-up-tray','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'arrow-up-tray','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Withdraw
                        </a>
                        <a href="<?php echo e(route('accounthistory')); ?>" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'document-text','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'document-text','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Transactions
                        </a>
                        <div class="border-t border-surface-border my-1"></div>
                        <a href="<?php echo e(route('logout')); ?>"
                           onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-loss/80 hover:bg-surface-overlay hover:text-loss transition-colors">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'arrow-right-on-rectangle','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'arrow-right-on-rectangle','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    
    <main class="transition-all duration-200 lg:ml-64 pt-16 min-h-screen">
        
        <div x-data="{ toasts: [] }"
             x-init="
                <?php if(Session::has('success')): ?>
                    toasts.push({ id: Date.now(), message: '<?php echo e(Session::get('success')); ?>', type: 'success' });
                    setTimeout(() => { toasts = toasts.filter(t => t.id !== toasts[0]?.id) }, 5000);
                <?php endif; ?>
                <?php if(Session::has('message')): ?>
                    toasts.push({ id: Date.now() + 1, message: '<?php echo e(Session::get('message')); ?>', type: 'error' });
                    setTimeout(() => { toasts = toasts.filter(t => t.id !== toasts[0]?.id) }, 5000);
                <?php endif; ?>
             "
             class="fixed top-20 right-4 z-50 space-y-2 w-80">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     :class="{
                        'bg-gain/10 border-gain/20 text-gain': toast.type === 'success',
                        'bg-loss/10 border-loss/20 text-loss': toast.type === 'error',
                        'bg-warning/10 border-warning/20 text-warning': toast.type === 'warning',
                     }"
                     class="border rounded-lg p-4 flex items-start gap-3 shadow-lg backdrop-blur-sm">
                    <span x-text="toast.message" class="text-sm flex-1"></span>
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="shrink-0 opacity-60 hover:opacity-100">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'x-mark','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </button>
                </div>
            </template>
        </div>

        <div class="p-4 lg:p-6 space-y-6">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

        
        <footer class="border-t border-surface-border py-6 px-6 mt-8">
            <p class="text-sm text-content-tertiary text-center">
                &copy; <?php echo e(date('Y')); ?> <a href="#" class="text-primary hover:text-primary-light transition-colors"><?php echo e($settings->site_name); ?></a>. All rights reserved.
            </p>
        </footer>
    </main>

    
    <?php if($showDepositModals): ?>
    <?php $__currentLoopData = $dmethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div x-data="{ open: false, copied: false }"
         @open-deposit-<?php echo e($item->id); ?>.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>
        
        <div x-show="open" x-transition class="relative w-full max-w-md bg-surface-raised border border-surface-border rounded-xl shadow-2xl overflow-hidden">

            
            <div class="flex items-center justify-between px-6 py-4 border-b border-surface-border">
                <h3 class="text-base font-semibold text-content-primary"><?php echo e($item->name); ?> Deposit</h3>
                <button @click="open = false" class="p-1 rounded-lg text-content-tertiary hover:text-content-primary hover:bg-surface-overlay transition-colors">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'x-mark','class' => 'w-4 h-4']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </button>
            </div>

            
            <div class="px-6 py-5 space-y-5">

                
                <div class="flex items-start gap-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?php echo e($item->wallet_address); ?>&bgcolor=1C2127&color=E8EAED"
                         alt="QR Code" class="w-24 h-24 rounded-lg border border-surface-border shrink-0">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-content-tertiary mb-1.5">Send <?php echo e($item->name); ?> to this address</p>
                        <div class="bg-surface-overlay rounded-lg px-3 py-2 border border-surface-border">
                            <p class="text-xs font-mono text-content-primary break-all leading-relaxed"><?php echo e($item->wallet_address); ?></p>
                        </div>
                        <button type="button"
                                @click="navigator.clipboard.writeText('<?php echo e($item->wallet_address); ?>'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:text-primary-light transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-.621-.504-1.125-1.125-1.125h-2.25" />
                            </svg>
                            <span x-show="!copied">Copy address</span>
                            <span x-show="copied" x-cloak class="text-gain">Copied!</span>
                        </button>
                    </div>
                </div>

                <div class="border-t border-surface-border/60"></div>

                
                <form action="<?php echo e(route('savedeposit')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="paymethd_method" value="<?php echo e($item->name); ?>">
                    <input type="hidden" name="mode" value="<?php echo e($item->name); ?>">

                    
                    <div>
                        <label class="text-xs font-medium text-content-tertiary mb-1.5 block">Amount (<?php echo \App\Helpers\CurrencyHelper::getUserSymbol(); ?>)</label>
                        <input type="number" name="amount" step="0.01" min="1" required placeholder="0.00"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                    </div>

                    
                    <div>
                        <label class="text-xs font-medium text-content-tertiary mb-1.5 block">Proof of Payment</label>
                        <label class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-dashed border-surface-border-light rounded-lg cursor-pointer hover:border-primary/40 hover:bg-primary/5 transition-colors group">
                            <svg class="w-4 h-4 text-content-tertiary group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span class="text-sm text-content-tertiary group-hover:text-content-secondary transition-colors">Choose file or drag here</span>
                            <input type="file" name="proof" required class="sr-only">
                        </label>
                    </div>

                    
                    <div class="flex gap-3 pt-1">
                        <button type="button" @click="open = false"
                                class="flex-1 bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-border rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Submit Deposit
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    
    <div x-data="{ open: false }"
         @open-other-deposit.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/60" @click="open = false"></div>
        <div x-show="open" x-transition class="relative w-full max-w-md bg-surface-raised border border-surface-border rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-content-primary">Other Deposit Method</h3>
                    <button @click="open = false" class="text-content-tertiary hover:text-content-primary"><?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'x-mark','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?></button>
                </div>
                <form method="POST" action="<?php echo e(route('otherpayment')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Full Name</label>
                        <input type="text" name="name" value="<?php echo e(Auth::user()->name); ?>" readonly
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Email</label>
                        <input type="email" name="email" value="<?php echo e(Auth::user()->email); ?>" readonly
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Deposit Type</label>
                        <select name="mode" required
                                class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="" disabled selected>Select method</option>
                            <option value="Litecoin">Litecoin</option>
                            <option value="BANK TRANSFER">Bank Transfer</option>
                            <option value="BITCOIN CASH">Bitcoin Cash</option>
                            <option value="USDT">USDT</option>
                            <option value="PAYPAL">PayPal</option>
                            <option value="WESTERN UNION">Western Union</option>
                            <option value="SKRILL">Skrill</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Amount</label>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="open = false" class="flex-1 bg-surface-overlay text-content-secondary hover:bg-surface-border rounded-lg py-2.5 text-sm font-medium transition-colors">Cancel</button>
                        <button type="submit" name="request_deposit" class="flex-1 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div x-data="{ open: false }"
         @open-mail-support.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/60" @click="open = false"></div>
        <div x-show="open" x-transition class="relative w-full max-w-lg bg-surface-raised border border-surface-border rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-content-primary">Contact Support</h3>
                    <button @click="open = false" class="text-content-tertiary hover:text-content-primary"><?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.icon','data' => ['name' => 'x-mark','class' => 'w-5 h-5']]); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?></button>
                </div>
                <form method="POST" action="<?php echo e(route('enquiry')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="to_email" value="<?php echo e($settings->site_name); ?> Support">
                    <input type="hidden" name="email" value="<?php echo e(Auth::user()->email); ?>">
                    <input type="hidden" name="name" value="<?php echo e(Auth::user()->name); ?>">
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Subject</label>
                        <input type="text" name="subject" required placeholder="How can we help?"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Message</label>
                        <textarea name="message" rows="5" required placeholder="Describe your issue..."
                                  class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary resize-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="open = false" class="flex-1 bg-surface-overlay text-content-secondary hover:bg-surface-border rounded-lg py-2.5 text-sm font-medium transition-colors">Cancel</button>
                        <button type="submit" name="contact" class="flex-1 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php echo \Livewire\Livewire::scripts(); ?>

    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->make('layouts.livechat', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/layouts/dash1.blade.php ENDPATH**/ ?>