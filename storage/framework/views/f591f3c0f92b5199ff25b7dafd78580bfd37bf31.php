<div>
    <?php if(Session::has('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition class="flex items-start gap-3 p-4 rounded-lg bg-gain/10 border border-gain/20 mb-4" role="alert">
            <?php echo $__env->make('components.icons.check-circle', ['class' => 'w-5 h-5 text-gain mt-0.5 shrink-0'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <p class="flex-1 text-sm text-gain"><?php echo e(Session::get('success')); ?></p>
            <button @click="show = false" class="text-gain/60 hover:text-gain transition shrink-0">
                <?php echo $__env->make('components.icons.x-mark', ['class' => 'w-4 h-4'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </button>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/components/success-alert.blade.php ENDPATH**/ ?>