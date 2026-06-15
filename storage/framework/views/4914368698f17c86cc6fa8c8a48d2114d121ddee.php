

<section class="bg-primary py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-white"><?php echo e($title); ?></h2>
        <?php if(isset($subtitle)): ?>
        <p class="text-white/80 mt-3 text-lg"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
        <a href="<?php echo e(route($buttonRoute ?? 'register')); ?>"
           class="inline-block mt-6 bg-white text-primary font-semibold rounded-lg px-8 py-3 hover:bg-gray-100 transition shadow-lg">
            <?php echo e($buttonText ?? 'Get Started'); ?>

        </a>
    </div>
</section>
<?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/home/partials/cta-banner.blade.php ENDPATH**/ ?>