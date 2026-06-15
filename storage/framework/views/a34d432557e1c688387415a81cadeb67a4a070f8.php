<?php
    $captchaA = rand(1, 12);
    $captchaB = rand(1, 12);
    $captchaAnswer = (string) ($captchaA + $captchaB);
?>


<?php $__env->startSection('title', 'Sign up'); ?>
<?php $__env->startSection('content'); ?>

<div class="w-full max-w-2xl mx-auto">
    
    <div class="text-center mb-8">
        <a href="/">
            <img src="<?php echo e(asset('storage/app/public/' . $settings->logo)); ?>" alt="<?php echo e($settings->site_name); ?>" class="h-12 mx-auto">
        </a>
    </div>

    
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8">
        
        <?php if(Session::has('status')): ?>
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-300"><?php echo e(session('status')); ?></p>
            </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-content-primary mb-1">Sign Up for Free</h1>
        <p class="text-content-tertiary text-sm mb-6">It's free to sign up and only takes a minute.</p>

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Full Name</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        placeholder="Enter your Name"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-loss"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Username</label>
                    <input type="text" name="username" value="<?php echo e(old('username')); ?>" required
                        placeholder="Enter Preferred Username"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-loss"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="username"
                        placeholder="Enter your email"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-loss"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Phone</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required maxlength="13"
                        placeholder="Enter your phone"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-loss"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Gender</label>
                    <select name="gender" required
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        <option value="" class="text-content-tertiary">Select Gender</option>
                        <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Female</option>
                        <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Male</option>
                        <option value="Others" <?php echo e(old('gender') == 'Others' ? 'selected' : ''); ?>>Others</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Country</label>
                    <select name="country" required
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        <?php echo $__env->make('auth.countries', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </select>
                </div>
            </div>

            
            <div class="mb-4">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Preferred Currency</label>
                <select name="currency_code" required
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    <?php $__currentLoopData = \App\Models\ExchangeRate::where('is_active', true)->orderBy('currency_code')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($rate->currency_code); ?>" style="background:#1a1d23;color:#e5e7eb" <?php echo e(old('currency_code', 'USD') == $rate->currency_code ? 'selected' : ''); ?>>
                            <?php echo e($rate->currency_code); ?> (<?php echo e(html_entity_decode($rate->currency_symbol)); ?>)<?php echo e($rate->currency_name ? ' — ' . $rate->currency_name : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['currency_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-loss"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="mt-1 text-xs text-content-tertiary">All balances and amounts will be displayed in this currency</p>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Password</label>
                    <input type="password" name="password" required autocomplete="new-password"
                        placeholder="Enter your password"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-loss"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        placeholder="Confirm Password"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>
            </div>

            
            <?php if(Session::has('ref_by')): ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Referral ID</label>
                    <input type="text" name="ref_by" value="<?php echo e(session('ref_by')); ?>" required
                        placeholder="Referral Code (Optional)"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>
            <?php endif; ?>

            
            <div class="mb-4">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Security Check</label>
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-sm font-medium text-content-primary select-none whitespace-nowrap">
                        <?php echo e($captchaA); ?> + <?php echo e($captchaB); ?> =
                    </div>
                    <input type="text" name="captcha" required inputmode="numeric"
                        placeholder="Answer"
                        class="w-24 bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors text-center">
                </div>
                <?php if($errors->has('captcha')): ?>
                    <p class="mt-1 text-sm text-loss"><?php echo e($errors->first('captcha')); ?></p>
                <?php endif; ?>
                <input type="hidden" name="captcha_confirmation" value="<?php echo e($captchaAnswer); ?>">
            </div>

            
            <div class="mb-6" x-data="{ selected: [] }">
                <label class="block text-sm font-medium text-content-secondary mb-2">Account Type</label>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = [
                        'Binary Option Trading' => 'Binary Options',
                        'Forex Trading' => 'Forex',
                        'Stock Trading' => 'Stocks',
                        'CryptoCurrency Investment' => 'Crypto',
                        'NFT Trading' => 'NFTs',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="account[]" value="<?php echo e($value); ?>" class="peer sr-only"
                            x-on:change="$event.target.checked ? selected.push('<?php echo e($value); ?>') : selected = selected.filter(v => v !== '<?php echo e($value); ?>')"
                        >
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm border transition-all
                                     border-surface-border text-content-tertiary
                                     peer-checked:border-primary/60 peer-checked:text-primary-light peer-checked:bg-primary/8
                                     hover:border-surface-border-light hover:text-content-secondary">
                            <?php echo e($label); ?>

                        </span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1.5 text-xs text-loss"><?php echo e($message); ?></p>
                <?php else: ?>
                    <p class="mt-1.5 text-xs text-content-tertiary">Select one or more</p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors">
                Register
            </button>
        </form>

        
        <div class="mt-6 text-sm text-content-tertiary">
            Already have an account? <a href="<?php echo e(route('login')); ?>" class="text-primary-light hover:text-primary transition-colors">Sign In</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest1', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/auth/register.blade.php ENDPATH**/ ?>