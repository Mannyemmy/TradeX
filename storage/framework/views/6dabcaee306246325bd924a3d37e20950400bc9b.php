<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('user.partials.ticker-tape', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('user.partials.quick-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('user.partials.page-header', ['title' => 'Transactions', 'subtitle' => 'View your deposit, withdrawal, and other transaction history'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden" x-data="{ tab: 'deposits' }">
        
        <div class="flex border-b border-surface-border">
            <button @click="tab = 'deposits'" :class="tab === 'deposits' ? 'text-primary border-primary' : 'text-content-tertiary border-transparent hover:text-content-secondary'"
                    class="flex-1 px-4 py-3.5 text-sm font-medium border-b-2 transition-colors text-center">
                Deposits
            </button>
            <button @click="tab = 'withdrawals'" :class="tab === 'withdrawals' ? 'text-primary border-primary' : 'text-content-tertiary border-transparent hover:text-content-secondary'"
                    class="flex-1 px-4 py-3.5 text-sm font-medium border-b-2 transition-colors text-center">
                Withdrawals
            </button>
            <button @click="tab = 'others'" :class="tab === 'others' ? 'text-primary border-primary' : 'text-content-tertiary border-transparent hover:text-content-secondary'"
                    class="flex-1 px-4 py-3.5 text-sm font-medium border-b-2 transition-colors text-center">
                Others
            </button>
        </div>

        
        <div x-show="tab === 'deposits'" class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Payment Mode</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-5 py-3.5 text-content-primary font-medium"><?php echo \App\Helpers\CurrencyHelper::formatForUser(is_numeric($deposit->amount) ? $deposit->amount : floatval($deposit->amount)); ?></td>
                        <td class="px-5 py-3.5 text-content-secondary"><?php echo e($deposit->payment_mode); ?></td>
                        <td class="px-5 py-3.5">
                            <?php if($deposit->status == 'Processed'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gain/10 text-gain"><?php echo e($deposit->status); ?></span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning"><?php echo e($deposit->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-content-tertiary text-xs"><?php echo e(\Carbon\Carbon::parse($deposit->created_at)->toDayDateTimeString()); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-content-tertiary">No deposit records found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div x-show="tab === 'withdrawals'" x-cloak class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">With Charges</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Mode</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-5 py-3.5 text-content-primary font-medium"><?php echo \App\Helpers\CurrencyHelper::formatForUser($withdrawal->amount); ?></td>
                        <td class="px-5 py-3.5 text-content-secondary"><?php echo \App\Helpers\CurrencyHelper::formatForUser($withdrawal->to_deduct); ?></td>
                        <td class="px-5 py-3.5 text-content-secondary"><?php echo e($withdrawal->payment_mode); ?></td>
                        <td class="px-5 py-3.5">
                            <?php if($withdrawal->status == 'Processed'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gain/10 text-gain"><?php echo e($withdrawal->status); ?></span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning"><?php echo e($withdrawal->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-content-tertiary text-xs"><?php echo e(\Carbon\Carbon::parse($withdrawal->created_at)->toDayDateTimeString()); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-content-tertiary">No withdrawal records found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div x-show="tab === 'others'" x-cloak class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Type</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Plan / Narration</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    <?php $__empty_1 = true; $__currentLoopData = $t_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-5 py-3.5 text-content-primary font-medium"><?php echo \App\Helpers\CurrencyHelper::formatForUser($history->amount); ?></td>
                        <td class="px-5 py-3.5 text-content-secondary"><?php echo e($history->type); ?></td>
                        <td class="px-5 py-3.5 text-content-secondary"><?php echo e($history->plan); ?></td>
                        
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-content-tertiary">No other transactions found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash1', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/user/transactions.blade.php ENDPATH**/ ?>