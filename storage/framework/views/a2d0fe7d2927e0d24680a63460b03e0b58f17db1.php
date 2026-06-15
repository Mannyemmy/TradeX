<div>
    <?php if(Session::has('message')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition class="flex items-start gap-3 p-4 rounded-lg bg-loss/10 border border-loss/20 mb-4" role="alert">
            <?php echo $__env->make('components.icons.exclamation-triangle', ['class' => 'w-5 h-5 text-loss mt-0.5 shrink-0'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <p class="flex-1 text-sm text-loss"><?php echo e(Session::get('message')); ?></p>
            <button @click="show = false" class="text-loss/60 hover:text-loss transition shrink-0">
                <?php echo $__env->make('components.icons.x-mark', ['class' => 'w-4 h-4'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </button>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/components/danger-alert.blade.php ENDPATH**/ ?>