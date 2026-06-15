

<?php $__env->startSection('title', 'Privacy Policy'); ?>

<?php $__env->startSection('content'); ?>


<section class="bg-body-bg py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text">Privacy Policy</h1>
        <div class="flex items-center justify-center gap-2 mt-3 text-sm text-body-muted">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-primary transition">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-primary">Privacy Policy</span>
        </div>
    </div>
</section>


<section class="bg-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="bg-body-bg rounded-xl border border-body-border p-6 md:p-10">
            <div class="flex gap-3 mb-6">
                <a href="<?php echo e(route('terms')); ?>" class="text-sm font-medium bg-body-bg text-body-muted px-3 py-1 rounded-full border border-body-border hover:text-primary transition">Risk Warning</a>
                <a href="<?php echo e(route('privacy')); ?>" class="text-sm font-medium bg-primary-subtle text-primary px-3 py-1 rounded-full hover:bg-primary hover:text-white transition">Privacy Policy</a>
            </div>

            <article class="prose prose-sm max-w-none text-body-muted leading-relaxed space-y-6">
                <h3 class="text-xl font-bold text-body-text">PRIVACY POLICY</h3>
                <p><?php echo e($settings->site_name); ?> is fully committed to the protection of the privacy of personal and financial information of its clients. We carefully explain how we handle the data of our clients and ensure its protection. By opening an account, the client hereby gives <?php echo e($settings->site_name); ?> its consent to such collection, processing, storage, and use of personal information by <?php echo e($settings->site_name); ?> as explained below.</p>

                <h4 class="text-lg font-semibold text-body-text">Collection of Personal Information</h4>
                <p><?php echo e($settings->site_name); ?> collects the necessary information required to open, transact, and safeguard your assets and your privacy, and provides you with the services you need. <?php echo e($settings->site_name); ?> gathers information from you and may, in certain circumstances, gather information from relevant banks and/or credit agencies, and/or other sources which help us better profile your requirements and preferences and provide improved services to you.</p>

                <h4 class="text-lg font-semibold text-body-text">The information <?php echo e($settings->site_name); ?> collects may include:</h4>

                <h5 class="text-base font-semibold text-body-text">Application information</h5>
                <p>Personal information you provide us with in your application form, such as your name, address, date of birth, email address, etc., in order to facilitate the evaluation of your application. The information you provide us with is also used for purposes of communicating with you.</p>

                <h5 class="text-base font-semibold text-body-text">Transaction information</h5>
                <p>Information about the anticipated volume and value of your transactions with us, and income information provided in order to enable the construction of your economic profile.</p>

                <h5 class="text-base font-semibold text-body-text">Verification information</h5>
                <p>Information necessary to verify your identity, such as an identification card, passport, or driver's license. This also includes background information we receive about you from public records, or from other entities not affiliated with <?php echo e($settings->site_name); ?>.</p>

                <h4 class="text-lg font-semibold text-body-text">Usage of Personal Information / Opt Out</h4>
                <p>No personal information will be shared with any third parties without the customer's permission.</p>
                <p><?php echo e($settings->site_name); ?> uses personal information only as required, in order to provide quality service and security to you. This information helps improve services, customize the browsing experience, and enables us to inform you of additional products, services, or promotions relevant to you and the products and services you need, as well as your consent for use of such data.</p>
                <p>If you do not want to receive information of this nature for any reason, please contact us at: <a href="mailto:<?php echo e($settings->contact_email); ?>" class="text-primary hover:underline"><?php echo e($settings->contact_email); ?></a></p>
                <p>Although you are not required to provide <?php echo e($settings->site_name); ?> with any of the personal information that we may request of you, please note that failure to do so, could result in <?php echo e($settings->site_name); ?> being unable to open your account, or provide you with the service you need.</p>
                <p>Whilst we attempt to ensure that all the information we hold about you is current, accurate, and complete, we urge you to immediately contact us if any of your personal details have changed.</p>

                <h4 class="text-lg font-semibold text-body-text">Protection of personal information</h4>
                <p>Any personal information you provide us will be treated as confidential, shared only within <?php echo e($settings->site_name); ?> and its affiliates, and will not be disclosed to any third party, except under any regulatory or legal proceedings.</p>
                <p>The personal information that you provide while registering yourself as a user of the site is protected. You can access your registration information through a password selected by you. This password is encrypted, known only to you, and will not be revealed to anyone else.</p>
                <p>Registration information is safely stored on secure servers that only authorized personnel have access to via a secure password. The company encrypts all personal information, as it is transferred to the company, thus making all of the necessary effort to prevent unauthorized parties from viewing any of this information.</p>

                <h4 class="text-lg font-semibold text-body-text">Affiliates and Partners</h4>
                <p><?php echo e($settings->site_name); ?> may work with affiliates and business introducers who refer new clients to the platform. If you were referred by an affiliate, that affiliate may have access to limited account information (such as your registration status). You consent to this sharing when you open an account through an affiliate link.</p>
                <p>If you are interested in our referral program, contact us at <a href="mailto:<?php echo e($settings->contact_email); ?>" class="text-primary hover:underline"><?php echo e($settings->contact_email); ?></a>.</p>

                <h4 class="text-lg font-semibold text-body-text">Non-affiliated third parties</h4>
                <p><?php echo e($settings->site_name); ?> does not sell, license, lease, or otherwise disclose personal information to third parties, except as described in this privacy statement.</p>
                <p><?php echo e($settings->site_name); ?> reserves the right to disclose information as necessary to credit reporting or collection agencies as reasonably required in order to provide services to you.</p>
                <p>In order to help us improve our services, <?php echo e($settings->site_name); ?> may involve third parties to help carry out certain internal functions, such as account processing, fulfillment, client service, client satisfaction surveys, or other data collection activities relevant to our business. Use of the shared information may also be used to provide professional, legal, or accounting advice to <?php echo e($settings->site_name); ?>. Use of shared information is strictly limited to the performance of the above, and is not permitted for any other use. All third parties with which <?php echo e($settings->site_name); ?> shares personal information are required to protect this information in accordance with all relevant legislation, and in a manner similar to the way <?php echo e($settings->site_name); ?> protects the same. <?php echo e($settings->site_name); ?> will not share personal information with third parties.</p>
                <p>A business introducer may have access to your information. You hereby unambiguously and unequivocally consent to the sharing of information with a business introducer.</p>
                <p>You acknowledge that in order to provide services to you, it may be necessary for information to be transferred outside of the European Economic area, and you consent to such a transfer.</p>

                <h4 class="text-lg font-semibold text-body-text">Regulatory Disclosure</h4>
                <p><?php echo e($settings->site_name); ?> reserves the right to disclose personal information to third parties where required by law, regulatory, law enforcement, or other government authority of a competent jurisdiction in order to protect our rights, and/or to comply with such legal proceedings. Such disclosure shall occur on a 'need-to-know' basis, unless otherwise instructed by a regulatory, or other government authority. Under such circumstances, <?php echo e($settings->site_name); ?> shall expressly inform the third party regarding the confidential nature of the information.</p>

                <h4 class="text-lg font-semibold text-body-text">Restriction of responsibility</h4>
                <p><?php echo e($settings->site_name); ?> is not responsible for the privacy policies, or the content of sites it links to, and has no control of the use or protection of information provided by clients or collected by those sites. Whenever a client chooses to link to a co-branded website, or to a linked web site, this client may be asked to provide proof of registration or other information. Please note that such information is recorded by a third party, and will be governed by the privacy policy of that third party.</p>

                <h4 class="text-lg font-semibold text-body-text">Use of "COOKIES"</h4>
                <p><?php echo e($settings->site_name); ?> uses cookies in order to secure your trading activities, and to enhance the performance of the <?php echo e($settings->site_name); ?> website. Cookies used by <?php echo e($settings->site_name); ?> do not contain personal information or other sensitive information.</p>
                <p><?php echo e($settings->site_name); ?> may share website usage statistics with reputable advertising companies, and with its affiliated marketing companies. The information collected by the advertising company is not personally identifiable.</p>
                <p>To administer and improve the <?php echo e($settings->site_name); ?> website, we may use third parties to track and analyze usage and statistical volume information. The third party may use cookies to track behavior, and may set cookies on <?php echo e($settings->site_name); ?>'s behalf. These cookies do not contain any personally identifiable information.</p>

                <h4 class="text-lg font-semibold text-body-text">Privacy Statement Updates</h4>
                <p>From time to time, <?php echo e($settings->site_name); ?> may update this Privacy Statement. In the event that <?php echo e($settings->site_name); ?> materially changes this Privacy Statement, including how we collect, process or use your personal information, the revised Privacy Statement will be posted to the website. The client agrees to accept posting of a revised Privacy Statement electronically on the website as actual notice to him or her. Any dispute over our Privacy Statement is subject to this notice, and our Customer Agreement. <?php echo e($settings->site_name); ?> encourages clients to periodically check back and review this policy, so that they always will know what information <?php echo e($settings->site_name); ?> collects, how <?php echo e($settings->site_name); ?> uses it, and to whom <?php echo e($settings->site_name); ?> may disclose it.</p>

                <h4 class="text-lg font-semibold text-body-text">Agreement</h4>
                <p>I have read, understood, and agreed to the above Privacy Policy Agreement, and I confirm that I have full power and authority to enter into this Agreement.</p>
            </article>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Downloads\tradexpromaxnew-MYDIGITALMARKETHUB\resources\views/home/privacy.blade.php ENDPATH**/ ?>