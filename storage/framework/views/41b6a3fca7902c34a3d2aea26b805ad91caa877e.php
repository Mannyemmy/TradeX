<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>


<section x-data="{ slide: 0, total: 3 }" x-init="setInterval(() => slide = (slide + 1) % total, 5000)" class="relative overflow-hidden">
    
    <div class="absolute inset-0">
        <img src="<?php echo e(asset('temp/front/assets/img/lea.jpg')); ?>" alt="" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-surface-base/80"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6">
        
        <div class="relative min-h-[480px] md:min-h-[520px]">
            <?php
                $slides = [
                    ['title' => 'Trade forex, crypto and CFDs from one account', 'desc' => 'Access global markets with transparent pricing and responsive order execution.'],
                    ['title' => 'Build your portfolio with confidence', 'desc' => 'Choose from multiple asset classes including stocks, commodities and digital currencies.'],
                    ['title' => 'Tools and insights to support your strategy', 'desc' => 'Use real-time charts, market analysis and risk-management features to make informed decisions.'],
                ];
            ?>
            <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div x-show="slide === <?php echo e($i); ?>" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 flex items-center justify-center">
                <div class="max-w-2xl text-center py-16">
                    <h1 class="font-serif text-3xl md:text-5xl font-bold text-content-primary leading-tight"><?php echo e($s['title']); ?></h1>
                    <p class="text-content-secondary text-lg mt-4"><?php echo e($s['desc']); ?></p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-6">
                        <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-6 py-3 transition">
                            Open Account
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="<?php echo e(route('trading')); ?>" class="inline-flex items-center text-content-secondary hover:text-white font-medium transition text-sm">
                            Learn about our platform
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="flex justify-center space-x-2 pb-6 relative z-10">
            <template x-for="i in total" :key="i">
                <button @click="slide = i - 1" :class="slide === i - 1 ? 'bg-primary w-8' : 'bg-content-tertiary w-2'" class="h-2 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>
</section>


<section class="bg-primary py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-5">
                <h2 class="font-serif text-2xl md:text-3xl font-bold text-white">A platform designed for clarity</h2>
                <p class="text-white/80 mt-2">Straightforward pricing, reliable execution and the tools you need.</p>
            </div>
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border-l-2 border-white/30 pl-4">
                        <p class="text-3xl font-bold text-white">Fast</p>
                        <p class="text-white text-sm font-medium">Order Execution</p>
                    </div>
                    <div class="border-l-2 border-white/30 pl-4">
                        <p class="text-3xl font-bold text-white">Flexible</p>
                        <p class="text-white text-sm font-medium">Leverage Options</p>
                    </div>
                    <div class="border-l-2 border-white/30 pl-4">
                        <p class="text-3xl font-bold text-white">Multi-Asset</p>
                        <p class="text-white text-sm font-medium">Trading Platform</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="mb-10">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-body-text">Why traders choose <?php echo e($settings->site_name); ?></h2>
            <p class="text-body-muted text-lg mt-2">We focus on what matters: transparent conditions, reliable technology and responsive support.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
                $features = [
                    ['icon' => 'in-cirro-2-icon-1.svg', 'title' => 'Competitive Spreads', 'desc' => 'We aim to provide tight spreads across major currency pairs and popular instruments so you can focus on your trading strategy.'],
                    ['icon' => 'in-cirro-2-icon-2.svg', 'title' => 'Account Security', 'desc' => 'Client funds are held in segregated accounts, and our platform uses SSL encryption and two-factor authentication for added protection.'],
                    ['icon' => 'in-cirro-2-icon-3.svg', 'title' => 'Responsive Platform', 'desc' => 'Our web-based trading interface provides real-time charts, order management tools and market data in a clean, accessible layout.'],
                    ['icon' => 'in-cirro-2-icon-4.svg', 'title' => 'Transparent Fees', 'desc' => 'No hidden charges. Our fee structure is published on our website so you can calculate trading costs before placing an order.'],
                ];
            ?>
            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm border border-body-border p-6 hover:shadow-md transition">
                <img src="<?php echo e(asset('temp/frontpage/img/' . $f['icon'])); ?>" alt="<?php echo e($f['title']); ?>" class="w-12 h-12" />
                <h3 class="text-lg font-semibold text-body-text mt-4"><?php echo e($f['title']); ?></h3>
                <p class="text-body-muted text-sm mt-2 leading-relaxed"><?php echo e($f['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-5">
                <span class="inline-block bg-primary-subtle text-primary text-xs font-semibold px-3 py-1 rounded-full mb-4">Market overview</span>
                <h2 class="font-serif text-3xl font-bold text-body-text">Track prices in real time</h2>
                <p class="text-body-muted text-lg mt-3">Monitor live market data, spot trends and identify opportunities across forex, crypto and commodities.</p>
            </div>
            <div class="lg:col-span-7">
                <div class="bg-body-bg rounded-xl border border-body-border overflow-hidden p-4">
                    <div id="tradingview-widget"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="bg-body-bg py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-body-text mb-3">Available markets</h2>
        <p class="text-body-muted text-lg mb-8 max-w-2xl">Diversify across multiple asset classes from a single account. Each market comes with its own risk profile — please read our <a href="<?php echo e(route('risk')); ?>" class="text-primary hover:underline">risk disclosure</a> before trading.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
            <?php
                $markets = [
                    ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'title' => 'Forex', 'desc' => 'Trade major, minor and exotic currency pairs with flexible leverage and real-time pricing. Forex markets operate 24 hours a day, five days a week.'],
                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Cryptocurrency', 'desc' => 'Access popular digital assets including Bitcoin and Ethereum. Crypto markets are open around the clock, but volatility can be significant.'],
                    ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Commodities', 'desc' => 'Gain exposure to precious metals like gold and silver, as well as energy products. Commodity prices are influenced by global supply and demand factors.'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Stocks & Indices', 'desc' => 'Trade CFDs on shares of leading companies and global indices. Stock trading carries risk of loss and past performance does not guarantee future results.'],
                ];
            ?>
            <?php $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm border border-body-border p-6 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-subtle flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($m['icon']); ?>"/></svg>
                    </div>
                    <h3 class="font-semibold text-body-text"><?php echo e($m['title']); ?></h3>
                </div>
                <p class="text-body-muted text-sm leading-relaxed"><?php echo e($m['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="font-serif text-3xl font-bold text-body-text">Get started in three steps</h2>
            <p class="text-body-muted text-lg mt-3">Opening an account is straightforward. Here's what to expect.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
                $steps = [
                    ['num' => '1', 'title' => 'Register your account', 'desc' => 'Complete the registration form with your details. We will ask you to verify your identity as part of our compliance process.'],
                    ['num' => '2', 'title' => 'Fund your account', 'desc' => 'Deposit funds using one of our supported payment methods. Your balance will be available once the transaction is confirmed.'],
                    ['num' => '3', 'title' => 'Start trading', 'desc' => 'Browse available markets, analyse charts and place your first trade. Use stop-loss orders to manage your risk.'],
                ];
            ?>
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-primary text-white font-bold text-lg flex items-center justify-center mx-auto"><?php echo e($step['num']); ?></div>
                <h3 class="font-semibold text-body-text mt-4"><?php echo e($step['title']); ?></h3>
                <p class="text-body-muted text-sm mt-2"><?php echo e($step['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-body-bg py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h6 class="text-center text-body-muted text-sm font-semibold uppercase tracking-wider mb-4">Partner support</h6>
        <?php echo $__env->make('home.partials.partner-logos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</section>


<section class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="order-last md:order-first">
                <img src="<?php echo e(asset('temp/frontpage/img/in-cirro-10-mockup.png')); ?>" alt="Mobile App" class="w-full max-w-md mx-auto" />
            </div>
            <div>
                <h2 class="font-serif text-3xl font-bold text-body-text">Trade on the go</h2>
                <p class="text-body-muted text-lg mt-3">Monitor your positions, place orders and manage your account from your mobile device. Available for iOS and Android.</p>
                <div class="flex items-center space-x-3 mt-6">
                    <a href="#"><img src="<?php echo e(asset('temp/frontpage/img/in-app-button-apple.svg')); ?>" alt="App Store" class="h-10" /></a>
                    <a href="#"><img src="<?php echo e(asset('temp/frontpage/img/in-app-button-google.svg')); ?>" alt="Google Play" class="h-10" /></a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-body-text">Account <span class="text-primary">Plans</span></h2>
            <p class="text-body-muted text-lg mt-3">Select a plan that fits your trading goals. You can upgrade at any time as your needs change.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm border <?php echo e($index === 1 ? 'border-primary border-2' : 'border-body-border'); ?> p-6 hover:shadow-md transition">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-body-text"><?php echo e($plan->name); ?></h3>
                        <span class="text-xs text-primary font-medium"><?php echo e($settings->currency); ?><?php echo e($plan->min_price); ?> - <?php echo e($settings->currency); ?><?php echo e($plan->max_price); ?></span>
                    </div>
                </div>
                <ul class="space-y-2.5">
                    <li class="flex items-center text-sm text-body-muted">
                        <svg class="w-4 h-4 text-primary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Up to <?php echo e($plan->increment_amount); ?>% return
                    </li>
                    <li class="flex items-center text-sm text-body-muted">
                        <svg class="w-4 h-4 text-primary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Multi-asset trading
                    </li>
                    <li class="flex items-center text-sm text-body-muted">
                        <svg class="w-4 h-4 text-primary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Referral bonus: <?php echo e($settings->currency); ?><?php echo e($plan->gift); ?>

                    </li>
                    <li class="flex items-center text-sm text-body-muted">
                        <svg class="w-4 h-4 text-primary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Email &amp; live chat support
                    </li>
                    <li class="flex items-center text-sm text-body-muted">
                        <svg class="w-4 h-4 text-primary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Withdrawals processed within 24 hours
                    </li>
                </ul>
                <a href="<?php echo e(route('register')); ?>" class="block mt-6 text-center bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg py-2.5 text-sm transition">Get Started</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <p class="text-center text-body-muted text-xs mt-6">Trading involves risk. Returns are not guaranteed. Please read our <a href="<?php echo e(route('risk')); ?>" class="text-primary hover:underline">risk disclosure</a> before investing.</p>
    </div>
</section>


<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="font-serif text-3xl font-bold text-body-text">Frequently asked questions</h2>
                <p class="text-body-muted text-lg mt-3">Quick answers to common questions about our platform and services.</p>
                <a href="<?php echo e(route('faq')); ?>" class="inline-flex items-center mt-4 text-primary hover:text-primary-dark font-medium transition">
                    View all FAQs
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div x-data="{ open: 0 }" class="space-y-0 divide-y divide-body-border">
                <?php
                    $faqs = [
                        ['q' => 'What is ' . $settings->site_name . '?', 'a' => $settings->site_name . ' is an online trading platform that provides access to forex, cryptocurrency, commodities and stock CFD markets. We offer a web-based interface with real-time charts, order management and account tools.'],
                        ['q' => 'How do I open an account?', 'a' => 'Click "Open Account", fill in your details and complete identity verification. Once verified, you can deposit funds and start trading. The process typically takes a few minutes.'],
                        ['q' => 'What security measures are in place?', 'a' => 'We use SSL encryption, two-factor authentication and segregated client accounts. Our platform undergoes regular security reviews to help protect your data and funds.'],
                        ['q' => 'How do I contact support?', 'a' => 'You can reach our support team via email at ' . $settings->contact_email . ' or through the live chat widget on our website. We aim to respond to all enquiries within one business day.'],
                    ];
                ?>
                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-4">
                    <button @click="open = open === <?php echo e($i); ?> ? null : <?php echo e($i); ?>" class="flex items-center justify-between w-full text-left">
                        <span class="font-semibold text-body-text pr-4"><?php echo e($faq['q']); ?></span>
                        <svg :class="open === <?php echo e($i); ?> ? 'rotate-180' : ''" class="w-5 h-5 text-body-muted flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === <?php echo e($i); ?>" x-collapse x-cloak>
                        <p class="text-body-muted text-sm mt-3 leading-relaxed"><?php echo e($faq['a']); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>


<?php echo $__env->make('home.partials.cta-banner', [
    'title' => 'Ready to start trading?',
    'subtitle' => 'Open a free account, complete verification and place your first trade today.',
    'buttonText' => 'Open Account',
    'buttonRoute' => 'register',
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php echo $__env->make('home.partials.live-activity', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.8/dist/cdn.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/home/index.blade.php ENDPATH**/ ?>