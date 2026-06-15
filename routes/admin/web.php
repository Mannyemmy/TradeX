<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\LogicController;
use App\Http\Controllers\Admin\AdminLoanController;
use App\Http\Controllers\Admin\ManageTradeController;
use App\Http\Controllers\Admin\ExpertController;
use App\Http\Controllers\Admin\NftController;
use App\Http\Controllers\Admin\AdminBidController;
use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\Admin\ManageDepositController;
use App\Http\Controllers\Admin\ManageWithdrawalController;
use App\Http\Controllers\Admin\InvPlanController;
use App\Http\Controllers\Admin\ManageAdminController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\Settings\AppSettingsController;
use App\Http\Controllers\Admin\Settings\ReferralSettings;
use App\Http\Controllers\Admin\Settings\PaymentController;
use App\Http\Controllers\Admin\Settings\SubscriptionSettings;
use App\Http\Controllers\Admin\IpaddressController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\ClearCacheController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\ManageAssetController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\SignalPlanController;
use App\Http\Controllers\Admin\SignalController;
use App\Http\Controllers\Admin\TopupController;
use App\Http\Controllers\Admin\TradingAssetController;
use App\Http\Controllers\Admin\PreIpoController;
use App\Http\Controllers\Admin\StockAdminController;
use App\Http\Controllers\Admin\ManageCopyTradesController;
use App\Http\Controllers\Admin\BotTradingController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\Settings\ThemeColorController;
use App\Http\Controllers\Admin\Settings\ExchangeRateController;
use Illuminate\Support\Facades\Route;

// Admin Login Routes validate_admin

Route::prefix('adminlogin')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('remedylogin', 'showLoginForm')->name('adminloginform')->middleware('adminguest');
        Route::post('login', 'adminlogin')->name('adminlogin');
        Route::post('logout', 'adminlogout')->name('adminlogout');
        Route::get('dashboard', 'validate_admin')->name('validate_admin');
         Route::get(' validate_admin', 'showLoginForm')->name('adminloginform')->middleware('adminguest');
    });
});

Route::controller(TwoFactorController::class)->group(function () {
    // Two Factor controller for Admin.
    Route::get('admin/2fa', 'showTwoFactorForm')->name('2fa');
    Route::post('admin/twofa', 'verifyTwoFactor')->name('twofalogin');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('admin/forgot-password', 'forgotPassword')->name('admin.forgetpassword');
    Route::post('admin/send-request', 'sendPasswordRequest')->name('sendpasswordrequest');
    Route::get('/admin/reset-password/{email}', 'resetPassword')->name('resetview');
    Route::post('/reset-password-admin', 'validateResetPasswordToken')->name('restpass');
});

Route::middleware(['isadmin', '2fa'])->prefix('admin')->group(function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('dashboard', 'index')->name('admin.dashboard');
        Route::get('dashboard/plans', 'plans')->name('plans');
        Route::get('dashboard/new-plan', 'newplan')->name('newplan');
        Route::get('dashboard/edit-plan/{id}', 'editplan')->name('editplan');
        Route::get('dashboard/manageusers', 'manageusers')->name('manageusers');
        Route::get('dashboard/manage-crypto-assets', 'managecryptoasset')->name('managecryptoasset');
        Route::get('/dashboard/active-investments', 'activeInvestments')->name('activeinvestments');

        // CRM ROUTES
        Route::get('dashboard/calendar', 'calendar')->name('calendar');
        Route::get('dashboard/task', 'showtaskpage')->name('task');
        Route::get('dashboard/mtask', 'mtask')->name('mtask');
        Route::get('dashboard/viewtask', 'viewtask')->name('viewtask');
        Route::get('dashboard/customer', 'customer')->name('customer');
        Route::get('dashboard/leads', 'customer')->name('leads');
        Route::get('dashboard/leadsassign', 'customer')->name('leadsassign');
        Route::get('dashboard/user-plans/{id}',  'userplans')->name('user.plans');
        Route::get('dashboard/email-services',  'emailServices')->name('emailservices');
        Route::get('dashboard/about',  'aboutonlinetrade')->name('aboutonlinetrade');
        Route::get('dashboard/mwithdrawals', 'mwithdrawals')->name('mwithdrawals');
        Route::get('dashboard/mdeposits', 'mdeposits')->name('mdeposits');
        Route::get('dashboard/agents',  'agents')->name('agents');
        Route::get('dashboard/addmanager', 'addmanager')->name('addmanager');
        Route::get('dashboard/madmin', 'madmin')->name('madmin');
        Route::get('dashboard/msubtrade', 'msubtrade')->name('msubtrade');
        Route::get('dashboard/settings', 'settings')->name('settings');
        Route::get('dashboard/frontpage', 'frontpage')->name('frontpage');
        Route::get('dashboard/adduser', 'adduser')->name('adduser');
        // KYC Routes
        Route::get('dashboard/kyc-applications', 'kyc')->name('kyc');
        Route::get('dashboard/kyc-application/{id}', 'viewKycApplication')->name('viewkyc');
        Route::get('dashboard/adminprofile', 'adminprofile')->name('adminprofile');
    });

    Route::controller(KycController::class)->group(function () {
        Route::post('dashboard/processkyc', 'processKyc')->name('processkyc');
    });

    Route::controller(CrmController::class)->group(function () {
        Route::post('dashboard/addtask', 'addtask')->name('addtask');
        Route::post('dashboard/updatetask', 'updatetask')->name('updatetask');
        Route::get('dashboard/deltask/{id}', 'deltask')->name('deltask');
        Route::get('dashboard/markdone/{id}', 'markdone')->name('markdone');
        Route::post('dashboard/updateuser', 'updateuser')->name('updateuser');
        Route::get('dashboard/convert/{id}', 'convert')->name('convert');
        Route::post('dashboard/assign', 'assign')->name('assignuser');
    });

    Route::controller(ManageUsersController::class)->group(function () {
        Route::get('dashboard/user-wallet/{id}', 'userwallet')->name('user.wallet');
        Route::get('dashboard/fetchusers', 'fetchUsers')->name('fetchusers');
        Route::post('dashboard/sendmailsingle', 'sendmailtooneuser')->name('sendmailtooneuser');
        Route::post('dashboard/AddHistory', 'addHistory')->name('addhistory');
        Route::post('dashboard/edituser', 'edituser')->name('edituser');
        Route::post('dashboard/withdrawalcode', 'withdrawalcode')->name('withdrawalcode');
        Route::post('dashboard/winRate', 'winRate')->name('winRate');
        Route::post('dashboard/signalStrength', 'signalStrength')->name('signalStrength');
        Route::post('dashboard/notify', 'notify')->name('notify');
         Route::get('dashboard/getusers/{num}/{item}/{order}', 'getusers')->name('getusers');
        Route::get('dashboard/resetpswd/{id}', 'resetpswd')->name('resetpswd');
        Route::get('dashboard/login-activity/{id}', 'loginactivity')->name('loginactivity');
        Route::get('dashboard/clear-activity/{id}', 'clearactivity')->name('clearactivity');
        Route::get('dashboard/add-referral/{id}', 'showUsers')->name('showusers');
        Route::post('dashboard/add-referral', 'addReferral')->name('addref');
        Route::get('dashboard/switchuser/{id}', 'switchuser');
        Route::get('dashboard/clearacct/{id}', 'clearacct')->name('clearacct');
        Route::post('dashboard/saveuser', 'saveuser')->name('createuser');
        Route::get('dashboard/user-details/{id}', 'viewuser')->name('viewuser');
        Route::get('dashboard/email-verify/{id}', 'emailverify')->name('emailverify');
        Route::get('dashboard/uublock/{id}', 'ublock');
        Route::get('dashboard/uunblock/{id}', 'unblock');
        Route::get('dashboard/delsystemuser/{id}', 'delsystemuser');
        Route::get('dashboard/usertrademode/{id}/{action}', 'usertrademode');
        Route::get('dashboard/userwalletstatus/{id}/{action}', 'userwalletstatus');
        Route::post('dashboard/sendmailtoall', 'sendmailtoall')->name('sendmailtoall');
        Route::get('dashboard/deleteplan/{id}', 'deleteplan')->name('deleteplan');
        Route::get('dashboard/approveplan/{id}', 'approvePlan')->name('approveplan');
        Route::get('dashboard/markas/{status}/{id}', 'markplanas')->name('markas');
    });


    Route::controller(ManageDepositController::class)->group(function () {
        Route::get('dashboard/deldeposit/{id}', 'deldeposit')->name('deldeposit');
        Route::get('dashboard/pdeposit/{id}', 'pdeposit')->name('pdeposit');
        Route::get('dashboard/viewimage/{id}', 'viewdepositimage')->name('viewdepositimage');
        Route::post('dashboard/editamount', 'editamount')->name('editamount');
        Route::get('dashboard/deposits/{id}/edit', 'edit')->name('admin.deposits.edit');
        Route::post('dashboard/deposits/{id}/update', 'editDeposit')->name('admin.deposits.update');
    });

    Route::controller(ManageWithdrawalController::class)->group(function () {
        Route::post('dashboard/pwithdrawal', 'pwithdrawal')->name('pwithdrawal');
        Route::get('dashboard/process-withdrawal-request/{id}', 'processwithdraw')->name('processwithdraw');
        Route::get('dashboard/withdrawals/{id}/edit', 'edit')->name('admin.withdrawals.edit');
        Route::post('dashboard/withdrawals/{id}/update', 'editWithdrawal')->name('admin.withdrawals.update');
    });

    Route::controller(PaymentController::class)->group(function () {
        // Payment settings
        Route::post('dashboard/addwdmethod', 'addpaymethod')->name('addpaymethod');
        Route::put('dashboard/updatewdmethod', 'updatewdmethod');
        Route::get('dashboard/edit-method/{id}', 'editmethod')->name('editpaymethod');
        Route::get('dashboard/delete-method/{id}', 'deletepaymethod')->name('deletepaymethod');
        //enable and disbale payment method routes
        Route::get('dashboard/toggle-method-status/{id}', 'togglePaymentMethodStatus')->name('togglestatus');
        Route::put('dashboard/update-method', 'updatemethod')->name('updatemethod');
        Route::put('dashboard/paypreference', 'paypreference')->name('paypreference');
        Route::put('dashboard/updatecpd', 'updatecpd')->name('updatecpd');
        Route::put('dashboard/updategateway', 'updategateway')->name('updategateway');
        Route::put('dashboard/update-transfer-settings', 'updateTransfer')->name('updatetransfer');
        Route::get('dashboard/settings/payment-settings', 'paymentview')->name('paymentview');
    });

    Route::controller(TopupController::class)->group(function () {
        Route::post('dashboard/topup', 'topup')->name('topup');
    });


    ///wallet-connect

	Route::get('dashboard/mwalletconnect',  [HomeController::class, 'mwalletconnect'])->name('mwalletconnect');
	Route::get('dashboard/mwalletsettings',  [HomeController::class, 'mwalletsettings'])->name('mwalletsettings');
	Route::get('dashboard/mwalletdelete/{id}', [HomeController::class, 'mwalletdelete']);
	Route::post('dashboard/mwalletconnectsave', [HomeController::class, 'mwalletconnectsave']);
	Route::get('dashboard/user-wallet-disconnect/{walletId}', [HomeController::class, 'disconnectUserWallet'])->name('admin.wallet.disconnect');


    Route::controller(IpaddressController::class)->group(function () {
        Route::get('dashboard/ipaddress', 'index')->name('ipaddress');
        Route::get('dashboard/allipaddress', 'getaddress')->name('allipaddress');
        Route::get('dashboard/delete-ip/{id}', 'deleteip')->name('deleteip');
        Route::post('dashboard/add-ip', 'addipaddress')->name('addipaddress');
    });

    // NOTE: the legacy Admin\SettingsController was removed when its actions were
    // refactored into Admin\Settings\AppSettingsController (updatewebinfo,
    // updatepreference, updateemail, ...). The old route group that referenced
    // the now-missing controller has been dropped — nothing posts to these URLs.

    Route::controller(ManageAdminController::class)->group(function () {
        Route::get('dashboard/unblock/{id}', 'unblockadmin');
        Route::get('dashboard/ublock/{id}', 'blockadmin');
        Route::get('dashboard/deleletadmin/{id}', 'deleteadminacnt')->name('deleteadminacnt');
        Route::post('dashboard/editadmin', 'editadmin')->name('editadmin');
        Route::get('dashboard/adminchangepassword', 'adminchangepassword');
        Route::post('dashboard/adminupdatepass', 'adminupdatepass')->name('adminupdatepass');
        Route::get('dashboard/resetadpwd/{id}', 'resetadpwd')->name('resetadpwd');
        Route::post('dashboard/sendmail', 'sendmail')->name('sendmailtoadmin');
        Route::post('dashboard/changestyle', 'changestyle')->name('changestyle');
        Route::post('dashboard/saveadmin', 'saveadmin');
        Route::post('dashboard/update-profile', 'updateadminprofile')->name('upadprofile');
    });

    Route::controller(FrontendController::class)->group(function () {
        // This Route is for frontpage editing
        Route::post('dashboard/savefaq', 'savefaq')->name('savefaq');
        Route::post('dashboard/savetestimony', 'savetestimony')->name('savetestimony');
        Route::post('dashboard/saveimg', 'saveimg')->name('saveimg');
        Route::post('dashboard/savecontents', 'savecontents')->name('savecontents');
        //Update Frontend Pages
        Route::post('dashboard/updatefaq', 'updatefaq')->name('updatefaq');
        Route::post('dashboard/updatetestimony', 'updatetestimony')->name('updatetestimony');
        Route::post('dashboard/updatecontents', 'updatecontents')->name('updatecontents');
        Route::post('dashboard/updateimg', 'updateimg')->name('updateimg');
        // Delete fa and tes routes
        Route::get('dashboard/delfaq/{id}', 'delfaq');
        Route::get('dashboard/deltestimony/{id}', 'deltest');
        // privacy policy
        Route::get('dashboard/privacy-policy', 'termspolicy')->name('termspolicy');
        Route::post('dashboard/privacy-policy', 'savetermspolicy')->name('savetermspolicy');
    });

    Route::controller(InvPlanController::class)->group(function () {
        Route::post('dashboard/addplan', 'addplan')->name('addplan');
        Route::post('dashboard/updateplan', 'updateplan')->name('updateplan');
        Route::get('dashboard/trashplan/{id}', 'trashplan')->name('trashplan');
        Route::get('dashboard/investments/{id}/edit', 'editInvestment')->name('admin.investments.edit');
        Route::post('dashboard/investments/{id}/update', 'updateInvestment')->name('admin.investments.update');
    });

    Route::controller(LogicController::class)->group(function () {
        Route::post('dashboard/addagent', 'addagent');
        Route::get('dashboard/viewagent/{agent}', 'viewagent')->name('viewagent');
        Route::get('dashboard/delagent/{id}', 'delagent')->name('delagent');
    });

    Route::controller(AppSettingsController::class)->group(function () {
        // Update App Information
        Route::put('dashboard/updatewebinfo', 'updatewebinfo')->name('updatewebinfo');
        Route::put('dashboard/updatepreference', 'updatepreference')->name('updatepreference');
        Route::put('dashboard/updateemail', 'updateemail')->name('updateemailpreference');
        // Settings Routes
        Route::get('dashboard/settings/app-settings', 'appsettingshow')->name('appsettingshow');
        Route::post('update-theme', 'updateTheme')->name('theme.update');
        // API Configuration
        Route::post('dashboard/update-api-keys', 'updateApiKeys')->name('updateapikeys');
        Route::post('dashboard/test-api-connection', 'testApiConnection')->name('testapiconnection');
    });

    Route::controller(ReferralSettings::class)->group(function () {
        // Update referral settings info
        Route::put('dashboard/update-bonus', 'updaterefbonus')->name('updaterefbonus');
        Route::get('dashboard/settings/referral-settings', 'referralview')->name('refsetshow');
        // Update other bonus settings info
        Route::put('dashboard/other-bonus', 'otherBonus')->name('otherbonus');
    });

    Route::controller(ImportController::class)->group(function () {
        Route::get('download-doc', 'downloadDoc')->name('downlddoc');
        // This route is to import data from excel
        Route::post('dashboard/fileImport', 'fileImport')->name('fileImport');
    });

    Route::controller(SubscriptionSettings::class)->group(function () {
        Route::put('dashboard/updatesubfee', 'updatesubfee')->name('updatesubfee');
        Route::get('dashboard/settings/subscription-settings', 'index')->name('subview');
    });

    Route::controller(ThemeColorController::class)->group(function () {
        Route::get('dashboard/settings/color-settings', 'index')->name('admin.color-settings');
        Route::put('dashboard/update-colors', 'update')->name('admin.update-colors');
        Route::post('dashboard/reset-colors', 'reset')->name('admin.reset-colors');
    });

    Route::controller(ExchangeRateController::class)->group(function () {
        Route::get('dashboard/settings/exchange-rates', 'index')->name('admin.exchange-rates.index');
        Route::put('dashboard/settings/exchange-rates/{id}', 'update')->name('admin.exchange-rates.update');
        Route::post('dashboard/settings/exchange-rates/{id}/toggle', 'toggleActive')->name('admin.exchange-rates.toggle');
        Route::post('dashboard/settings/exchange-rates/fetch', 'fetchRates')->name('admin.exchange-rates.fetch');
        Route::post('dashboard/settings/exchange-rates/{id}/reset', 'resetRate')->name('admin.exchange-rates.reset');
    });

    Route::controller(ManageAssetController::class)->group(function () {
        // Crypto Asset
        Route::get('dashboard/setcryptostatus/{asset}/{status}', 'setassetstatus')->name('setassetstatus');
        Route::get('dashboard/useexchange/{value}', 'useexchange')->name('useexchange');
        Route::post('dashboard/exchangefee', 'exchangefee')->name('exchangefee');
    });


    Route::controller(MembershipController::class)->group(function () {
        //memebership module
        Route::get('/courses', 'showCourses')->name('courses');
        Route::post('/add-course', 'addCourse')->name('addcourse');
        Route::patch('/update-course', 'updateCourse')->name('updatecourse');
        Route::delete('/delete-course/{id}', 'deleteCourse')->name('deletecourse');
        Route::patch('/toggle-publish/{id}', 'togglePublish')->name('togglepublish');

        Route::get('/courses-lessons/{id}', 'showLessons')->name('lessons');
        Route::post('/add-lesson', 'addLesson')->name('addlesson');
        Route::patch('/update-lesson', 'updateLesson')->name('updatedlesson');
        Route::delete('/delete-lesson/{id}', 'deleteLesson')->name('deletelesson');
        Route::patch('/reorder-lesson', 'reorderLesson')->name('reorderlesson');

        Route::get('/categories', 'category')->name('categories');
        Route::post('/add-category', 'addCategory')->name('addcategory');
        Route::delete('/delete-cat/{id}', 'deleteCategory')->name('deletecategory');
        Route::get('lessons-without-course', 'lessonWithoutCourse')->name('less.nocourse');

        Route::get('/course-enrollments/{id}/edit', 'editEnrollment')->name('admin.enrollments.edit');
        Route::post('/course-enrollments/{id}/update', 'updateEnrollment')->name('admin.enrollments.update');
    });




    /*
		Trading signal modules
		users can subscribe to signal channel to get access
	*/

//signals
    Route::resource('signal', SignalController::class);

        //signalplan
    Route::resource('signal-plans', SignalPlanController::class);

    Route::controller(ManageTradeController::class)->group(function () {
        Route::get('managetrades', 'index')->name('admin.trades.index');
        Route::get('/managetrades/{id}','show')->name('admin.trades.show');

        Route::get('/admin/trades/create',  'create')->name('admin.trades.create');
        Route::post('/admin/trades/store', 'store')->name('admin.trades.store');
        Route::get('/admin/users/search','search')->name('admin.users.search');
        Route::get('/admin/trades/{trade}/edit', 'edit')->name('admin.trades.edit');
        Route::put('/admin/trades/{trade}',  'update')->name('admin.trades.update');
        Route::post('/mangetrades/{id}/update-profit-loss', 'updateProfitLoss')->name('admin.trades.updateProfitLoss');
        Route::post('/admin/trades/bulk-settle', 'bulkSettle')->name('admin.trades.bulkSettle');
    });

    // Trading Asset Management
    Route::controller(TradingAssetController::class)->prefix('assets')->group(function () {
        Route::get('/', 'index')->name('admin.assets.index');
        Route::post('/', 'store')->name('admin.assets.store');
        Route::get('{id}/edit', 'edit')->name('admin.assets.edit');
        Route::put('{id}', 'update')->name('admin.assets.update');
        Route::delete('{id}', 'destroy')->name('admin.assets.destroy');
        Route::post('{id}/toggle', 'toggleActive')->name('admin.assets.toggle');
        Route::post('{id}/price', 'updatePrice')->name('admin.assets.updatePrice');
        Route::get('refresh', 'refreshPrices')->name('admin.assets.refresh');
    });

    Route::resource('experts', ExpertController::class)->names('admin.experts');
    Route::get('experts/{expert}/toggle', [ExpertController::class, 'toggleActive'])->name('admin.experts.toggle');

    // Copy Trades Management
    Route::get('copy-trades', [ManageCopyTradesController::class, 'index'])->name('admin.copy-trades.index');
    Route::get('copy-trades/{position}', [ManageCopyTradesController::class, 'show'])->name('admin.copy-trades.show');
    Route::post('copy-trades/{position}/settle', [ManageCopyTradesController::class, 'settle'])->name('admin.copy-trades.settle');
    Route::post('copy-trades/{position}/stop', [ManageCopyTradesController::class, 'stop'])->name('admin.copy-trades.stop');
    Route::post('copy-trades/{position}/adjust', [ManageCopyTradesController::class, 'adjustProfit'])->name('admin.copy-trades.adjust');
    Route::post('copy-trades/bulk-settle', [ManageCopyTradesController::class, 'bulkSettle'])->name('admin.copy-trades.bulk-settle');

    // ── Bot Trading ─────────────────────────────────
    Route::controller(BotTradingController::class)->prefix('bot-trading')->group(function () {
        Route::get('/', 'index')->name('admin.bot-trading.index');
        Route::get('/create', 'create')->name('admin.bot-trading.create');
        Route::post('/', 'store')->name('admin.bot-trading.store');
        Route::get('/{bot}/edit', 'edit')->name('admin.bot-trading.edit');
        Route::put('/{bot}', 'update')->name('admin.bot-trading.update');
        Route::delete('/{bot}', 'destroy')->name('admin.bot-trading.destroy');
        Route::post('/{bot}/toggle', 'toggleActive')->name('admin.bot-trading.toggle');
        Route::get('/subscriptions', 'subscriptions')->name('admin.bot-trading.subscriptions');
        Route::get('/subscriptions/{subscription}', 'showSubscription')->name('admin.bot-trading.subscription');
        Route::get('/subscriptions/{subscription}/edit', 'editSubscription')->name('admin.bot-trading.subscription-edit');
        Route::post('/subscriptions/{subscription}/update', 'updateSubscription')->name('admin.bot-trading.subscription-update');
        Route::post('/subscriptions/{subscription}/settle', 'settleSubscription')->name('admin.bot-trading.settle');
        Route::post('/subscriptions/{subscription}/adjust', 'adjustProfit')->name('admin.bot-trading.adjust');
        Route::post('/subscriptions/bulk-settle', 'bulkSettle')->name('admin.bot-trading.bulk-settle');
    });

    // ── Support Tickets ─────────────────────────────
    Route::get('dashboard/support-tickets', [AdminSupportController::class, 'index'])->name('admin.support.index');
    Route::get('dashboard/support-tickets/{ticket}', [AdminSupportController::class, 'show'])->name('admin.support.show');
    Route::post('dashboard/support-tickets/{ticket}/reply', [AdminSupportController::class, 'reply'])->name('admin.support.reply');
    Route::put('dashboard/support-tickets/{ticket}/status', [AdminSupportController::class, 'updateStatus'])->name('admin.support.status');

    // ── NFT Module ──────────────────────────────────
    Route::prefix('admin/nfts')->name('admin.nfts.')->group(function () {
        Route::get('/', [NftController::class, 'index'])->name('index');
        Route::get('/create', [NftController::class, 'create'])->name('create');
        Route::post('/', [NftController::class, 'store'])->name('store');
        Route::get('/{nft}/edit', [NftController::class, 'edit'])->name('edit');
        Route::put('/{nft}', [NftController::class, 'update'])->name('update');
        Route::delete('/{nft}', [NftController::class, 'destroy'])->name('destroy');
        Route::post('/{nft}/toggle-featured', [NftController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{nft}/toggle-approval', [NftController::class, 'toggleApproval'])->name('toggle-approval');
        Route::get('/sold', [NftController::class, 'soldNFTs'])->name('sold');
        Route::get('/transfers', [NftController::class, 'transfers'])->name('transfers');
    });

    // NFT Categories
    Route::prefix('admin/nft-categories')->name('admin.nft.categories.')->group(function () {
        Route::get('/', [NftController::class, 'categories'])->name('index');
        Route::post('/', [NftController::class, 'storeCategory'])->name('store');
        Route::put('/{category}', [NftController::class, 'updateCategory'])->name('update');
        Route::delete('/{category}', [NftController::class, 'destroyCategory'])->name('destroy');
    });

    // NFT Collections
    Route::prefix('admin/nft-collections')->name('admin.nft.collections.')->group(function () {
        Route::get('/', [NftController::class, 'collections'])->name('index');
        Route::get('/create', [NftController::class, 'createCollection'])->name('create');
        Route::post('/', [NftController::class, 'storeCollection'])->name('store');
        Route::get('/{collection}/edit', [NftController::class, 'editCollection'])->name('edit');
        Route::put('/{collection}', [NftController::class, 'updateCollection'])->name('update');
        Route::delete('/{collection}', [NftController::class, 'destroyCollection'])->name('destroy');
        Route::post('/{collection}/toggle-featured', [NftController::class, 'toggleCollectionFeatured'])->name('toggle-featured');
    });



    //loan plans
    Route::get('/admin/loan-plans', [AdminLoanController::class, 'index'])->name('admin.loans.index');
    Route::get('/admin/loan-plans/create', [AdminLoanController::class, 'createPlan'])->name('admin.loan-plans.create');
    Route::post('/admin/loan-plans', [AdminLoanController::class, 'storePlan'])->name('admin.loan-plans.store');
    Route::get('/admin/loan-plans/{plan}/edit', [AdminLoanController::class, 'editPlan'])->name('admin.loan-plans.edit');
    Route::put('/admin/loan-plans/{plan}', [AdminLoanController::class, 'updatePlan'])->name('admin.loan-plans.update');
    Route::put('/admin/loan-plans/{plan}/toggle', [AdminLoanController::class, 'togglePlan'])->name('admin.loan-plans.toggle');

    //loan applications
    Route::get('/admin/loans/{loan}', [AdminLoanController::class, 'show'])->name('admin.loans.show');
    Route::get('/admin/loans/{loan}/edit', [AdminLoanController::class, 'editLoan'])->name('admin.loans.edit');
    Route::put('/admin/loans/{loan}/editupdate', [AdminLoanController::class, 'updateLoan'])->name('admin.loans.editupdate');
    Route::put('/admin/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('admin.loans.approve');
    Route::put('/admin/loans/{loan}/reject', [AdminLoanController::class, 'reject'])->name('admin.loans.reject');
    Route::put('/admin/loans/{loan}/default', [AdminLoanController::class, 'markDefaulted'])->name('admin.loans.default');

   // NFT Bids
   Route::get('/admin/bids', [AdminBidController::class, 'bidsForApproval'])->name('admin.bids.index');
   Route::post('/admin/bids/{bid}/approve', [AdminBidController::class, 'approveBid'])->name('admin.bids.approve');
   Route::post('/admin/bids/{bid}/reject', [AdminBidController::class, 'rejectBid'])->name('admin.bids.reject');

    // Pre-IPO Module
    Route::controller(PreIpoController::class)->prefix('admin/pre-ipo')->name('admin.pre-ipo.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/all/holdings', 'allHoldings')->name('all-holdings');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::put('/{id}/status', 'updateStatus')->name('status');
        Route::put('/{id}/price', 'updatePrice')->name('price');
        Route::get('/{id}/holdings', 'holdings')->name('holdings');
        Route::get('/{id}/price-history', 'priceHistoryApi')->name('price-history');
    });

    // Stock Shares Module
    Route::controller(StockAdminController::class)->prefix('admin/stock-shares')->name('admin.stocks.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/trades', 'trades')->name('trades');
        Route::get('/user/{userId}', 'userPositions')->name('user-positions');
        Route::post('/positions', 'createPosition')->name('create-position');
        Route::get('/positions/{id}/edit', 'editPosition')->name('edit-position');
        Route::put('/positions/{id}', 'updatePosition')->name('update-position');
        Route::delete('/positions/{id}', 'deletePosition')->name('delete-position');
        Route::get('/trades/{id}/edit', 'editTrade')->name('edit-trade');
        Route::put('/trades/{id}', 'updateTrade')->name('update-trade');
    });

    // clear cache
    Route::get('dashboard/clearcache', [ClearCacheController::class, 'clearcache'])->name('clearcache');

    // ── Admin Notifications ──────────────────────────
    Route::get('notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications');
    Route::get('notifications/unread', [AdminNotificationController::class, 'unread'])->name('admin.notifications.unread');
    Route::post('notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.readAll');
    Route::post('notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::delete('notifications/{id}', [AdminNotificationController::class, 'destroy'])->name('admin.notifications.destroy');

    // Inline clear-cache route removed — use the controller-based route above instead.
    // The previous inline route truncated the sessions table which logs out ALL users.
});
