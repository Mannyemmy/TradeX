<div>
    <?php if(Auth::user()->taxtype=='on'): ?>
        <div class="flex items-start gap-3 p-4 rounded-lg bg-warning/10 border border-warning/20 mb-4" role="alert">
            <?php echo $__env->make('components.icons.exclamation-triangle', ['class' => 'w-5 h-5 text-warning mt-0.5 shrink-0'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="text-sm text-warning">
                <p>You are required to pay Tax fee of <?php echo \App\Helpers\CurrencyHelper::formatForUser(Auth::user()->taxamount); ?>.</p>
                <p class="mt-1">Contact support at <?php echo e($settings->contact_email); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/components/alert.blade.php ENDPATH**/ ?>