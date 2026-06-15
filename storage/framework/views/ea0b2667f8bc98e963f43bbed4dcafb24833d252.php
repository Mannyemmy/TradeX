<?php $__env->startSection('title', 'Forgot your password'); ?>
<?php $__env->startSection('content'); ?>

<div class="w-full max-w-md mx-auto">
    
    <div class="text-center mb-8">
        <a href="/">
            <img src="<?php echo e(asset('storage/app/public/' . $settings->logo)); ?>" alt="<?php echo e($settings->site_name); ?>" class="h-12 mx-auto">
        </a>
    </div>

    
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8">
        
        <?php if(Session::has('message')): ?>
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-300"><?php echo e(Session::get('message')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('status')): ?>
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-emerald-300"><?php echo e(session('status')); ?></p>
            </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-content-primary mb-1">Forgot Password</h1>
        <p class="text-content-tertiary text-sm mb-6">Don't worry! We will help you recover your password.</p>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-6">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Email Address</label>
                <input type="email" name="email" required
                    placeholder="Enter your email"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1.5 text-sm text-loss"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors">
                Request Reset Link
            </button>
        </form>

        <div class="mt-6 text-sm text-content-tertiary space-y-1">
            <p>Did you remember your password?</p>
            <p>Try to <a href="<?php echo e(route('login')); ?>" class="text-primary-light hover:text-primary transition-colors">Sign in</a></p>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest1', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>