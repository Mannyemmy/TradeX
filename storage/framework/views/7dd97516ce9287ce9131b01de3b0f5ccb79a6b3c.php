
<footer class="bg-surface-base border-t border-surface-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1">
                <img src="<?php echo e(asset('storage/app/public/' . $settings->logo)); ?>" alt="<?php echo e($settings->site_name); ?>" class="h-10 w-auto mb-4" />
                <a href="mailto:<?php echo e($settings->contact_email); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">
                    <?php echo e($settings->contact_email); ?>

                </a>
            </div>

            
            <div>
                <h4 class="text-content-primary font-semibold text-sm uppercase tracking-wider mb-4">Company</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo e(route('about')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">About</a></li>
                    <li><a href="<?php echo e(route('service')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">Careers</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">Contact</a></li>
                </ul>
            </div>

            
            <div>
                <h4 class="text-content-primary font-semibold text-sm uppercase tracking-wider mb-4">Resources</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo e(route('trading')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">WebTrader</a></li>
                    <li><a href="<?php echo e(route('safety')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">Security</a></li>
                    <li><a href="<?php echo e(route('faq')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">Legal Docs</a></li>
                    <li><a href="<?php echo e(route('pricing')); ?>" class="text-content-secondary hover:text-primary-light text-sm transition">Markets</a></li>
                </ul>
            </div>

            
            <div>
                <h4 class="text-content-primary font-semibold text-sm uppercase tracking-wider mb-4">Connect</h4>
                <div class="flex items-center space-x-4 mb-6">
                    
                    <a href="#" class="text-content-secondary hover:text-primary-light transition" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    
                    <a href="#" class="text-content-secondary hover:text-primary-light transition" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    
                    <a href="#" class="text-content-secondary hover:text-primary-light transition" aria-label="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
                
                <img src="<?php echo e(asset('temp/frontpage/img/security.png')); ?>" alt="Security Verified" class="h-12 opacity-80" />
            </div>
        </div>

        
        <div class="border-t border-surface-border mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between">
            <p class="text-content-tertiary text-xs">
                &copy; <?php echo e(date('Y')); ?> <?php echo e($settings->site_name); ?>. All Rights Reserved.
            </p>
            <div class="flex items-center space-x-4 mt-3 sm:mt-0">
                <a href="<?php echo e(route('terms')); ?>" class="text-content-tertiary hover:text-content-secondary text-xs transition">Terms</a>
                <a href="<?php echo e(route('privacy')); ?>" class="text-content-tertiary hover:text-content-secondary text-xs transition">Privacy</a>
                <a href="<?php echo e(route('risk')); ?>" class="text-content-tertiary hover:text-content-secondary text-xs transition">Risk Disclosure</a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/home/partials/footer.blade.php ENDPATH**/ ?>