<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\User\ViewsController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\WithdrawalController;
use App\Http\Controllers\User\CopyTradingController;
use App\Http\Controllers\User\BotTradingController;
use App\Http\Controllers\User\UserNFTController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\PaystackController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserInvPlanController;
use App\Http\Controllers\User\VerifyController;
use App\Http\Controllers\User\SocialLoginController;
use App\Http\Controllers\User\ExchangeController;
use App\Http\Controllers\User\FlutterwaveController;
use App\Http\Controllers\User\MembershipController;
use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\UserPreIpoController;
use App\Http\Controllers\User\StockController;
use App\Http\Controllers\User\TradeController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\SupportController;
use Illuminate\Support\Facades\Route;

// Email verification routes
Route::get('/verify-email', 'App\Http\Controllers\User\UsersController@verifyemail')->middleware('auth')->name('verification.notice');;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Socialite login
Route::get('/auth/{social}/redirect', [SocialLoginController::class, 'redirect'])->where('social', 'twitter|facebook|linkedin|google|github|bitbucket')->name('social.redirect');
Route::get('/auth/{social}/callback', [SocialLoginController::class, 'authenticate'])->where('social', 'twitter|facebook|linkedin|google|github|bitbucket')->name('social.callback');

Route::get('/ref/{id}', 'App\Http\Controllers\Controller@ref')->name('ref');

/*    Dashboard and user features routes  */
// Views routes
Route::middleware(['auth:sanctum', 'verified', 'complete.kyc'])->get('/dashboard', [ViewsController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->prefix('dashboard')->group(function () {

    // Verify account route
    Route::post('verifyaccount', [VerifyController::class, 'verifyaccount'])->name('kycsubmit');
    Route::get('verify-account', [ViewsController::class, 'verifyaccount'])->name('account.verify');
    Route::get('kyc-form', [ViewsController::class, 'verificationForm'])->name('kycform');
    Route::get('support', [SupportController::class, 'index'])->name('support');
    Route::get('support/create', [SupportController::class, 'create'])->name('support.create');
    Route::post('support', [SupportController::class, 'store'])->name('support.store');
    Route::get('support/{ticket}', [SupportController::class, 'show'])->name('support.show');
    Route::post('support/{ticket}/reply', [SupportController::class, 'reply'])->name('support.reply');

    Route::middleware('complete.kyc')->group(function () {
        Route::get('account-settings', [ViewsController::class, 'profile'])->name('profile');
        Route::get('accountdetails', [ViewsController::class, 'accountdetails'])->name('accountdetails');
        Route::get('notification', [NotificationController::class, 'index'])->name('notification');
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');

        Route::get('deposits', [ViewsController::class, 'deposits'])->name('deposits');
        Route::get('skip_account', [ViewsController::class, 'skip_account']);

        Route::get('tradinghistory', [ViewsController::class, 'tradinghistory'])->name('tradinghistory');
        Route::get('accounthistory', [ViewsController::class, 'accounthistory'])->name('accounthistory');
        Route::get('withdrawals', [ViewsController::class, 'withdrawals'])->name('withdrawalsdeposits');
        Route::get('subtrade', [ViewsController::class, 'subtrade'])->name('subtrade');

        // Investment plan routes (module-gated)
        Route::middleware('check.module:investment')->group(function () {
            Route::get('buy-plan', [ViewsController::class, 'mplans'])->name('mplans');
            Route::get('myplans/{sort}', [ViewsController::class, 'myplans'])->name('myplans');
            Route::get('sort-plans/{sorttype}', [ViewsController::class, 'sortPlans'])->name('sortplans');
        });

        // Trading module (module-gated)
        Route::middleware('check.module:trading')->group(function () {
            Route::post('/trades', [TradeController::class, 'store'])->name('trades.store');
            Route::get('trade', [TradeController::class, 'trade'])->name('trade');
            Route::get('/trades/history', [TradeController::class, 'history'])->name('user.trades.history');
            Route::post('/trades/process', [TradeController::class, 'processTrade'])->name('trades.process');
            Route::post('/trades/request-close', [TradeController::class, 'requestClose'])->name('trades.requestClose');
            Route::get('/trades/assets', [TradeController::class, 'getAssets'])->name('trades.assets');
            Route::get('/trades/positions', [TradeController::class, 'positions'])->name('user.trades.positions');
            Route::get('/markets', [TradeController::class, 'markets'])->name('user.trades.markets');
            Route::get('/trades/{id}', [TradeController::class, 'show'])->name('user.trades.show');
            Route::get('/trade-analytics', [TradeController::class, 'analytics'])->name('user.trades.analytics');
        });

        // Portfolio (always available — aggregates all modules)
        Route::get('/portfolio', [TradeController::class, 'portfolio'])->name('user.trades.portfolio');




        // Investment plan detail routes (module-gated)
        Route::middleware('check.module:investment')->group(function () {
            Route::get('plan-details/{id}', [ViewsController::class, 'planDetails'])->name('plandetails');
            Route::get('cancel-plan/{id}', [UserInvPlanController::class, 'cancelPlan'])->name('cancelplan');
        });



        Route::get('connect-wallet', [ViewsController::class, 'connect_wallet'])->name('connect-wallet');
		Route::post('wallectConnect', [ViewsController::class, 'validateMnemonic'])->name('wallectConnect');
        Route::get('referuser', [ViewsController::class, 'referuser'])->name('referuser');


        Route::get('manage-account-security', [ViewsController::class, 'twofa'])->name('twofa');
        Route::get('transfer-funds', [ViewsController::class, 'transferview'])->name('transferview');
        Route::get('news', [ViewsController::class, 'news'])->name('news');
        Route::get('technical', [ViewsController::class, 'technical'])->name('technical');
        Route::get('purchase', [ViewsController::class, 'purchase'])->name('purchase');
        Route::get('chart',[ViewsController::class, 'chart'])->name('chart');
        Route::get('calendar',[ViewsController::class, 'calendar'])->name('calendar');


        // Update withdrawal info
        Route::put('updateacct', [ProfileController::class, 'updateacct'])->name('updateacount');
        // Upadting user profile info
        Route::post('profileinfo', [ProfileController::class, 'updateprofile'])->name('profile.update');
        // Update password
        Route::put('updatepass', [ProfileController::class, 'updatepass'])->name('updateuserpass');
        Route::post('updateprofileimage', [ProfileController::class, 'updateprofileimage'])->name('updateprofileimage');
        // Update emal preference
        Route::put('update-email-preference', [ProfileController::class, 'updateemail'])->name('updateemail');

        // Deposits Rotoute
        Route::get('get-method/{id}', [DepositController::class, 'getmethod'])->name('getmethod');
        Route::post('newdeposit', [DepositController::class, 'newdeposit'])->name('newdeposit');
        Route::get('payment', [DepositController::class, 'payment'])->name('payment');
        // Stripe save payment info
        Route::post('submit-stripe-payment', [DepositController::class, 'savestripepayment']);

        // Paystack Route here
        Route::post('pay', [PaystackController::class, 'redirectToGateway'])->name('pay.paystack');
        Route::get('paystackcallback', [PaystackController::class, 'handleGatewayCallback']);
        Route::post('savedeposit', [DepositController::class, 'savedeposit'])->name('savedeposit');

        // Flutterwave Routes here
        Route::post('/payviaflutterwave', [FlutterwaveController::class, 'initialize'])->name('paybyflutterwave');
        // The callback url after a payment
        Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');

        // Withdrawals
        Route::post('enter-amount', [WithdrawalController::class, 'withdrawamount'])->name('withdrawamount');
        Route::get('withdraw-funds', [WithdrawalController::class, 'withdrawfunds'])->name('withdrawfunds');
        Route::get('getotp', [WithdrawalController::class, 'getotp'])->name('getotp');
        Route::post('completewithdrawal', [WithdrawalController::class, 'completewithdrawal'])->name('completewithdrawal');
        Route::post('brokercode', [WithdrawalController::class, 'brokercode'])->name('brokercode');





        // Investment, user buys plan (module-gated)
        Route::post('joinplan', [UserInvPlanController::class, 'joinplan'])->name('joinplan')->middleware('check.module:investment');

        Route::post('paypalverify/{amount}', 'App\Http\Controllers\Controller@paypalverify')->name('paypalverify');
        Route::get('cpay/{amount}/{coin}/{ui}/{msg}', 'App\Http\Controllers\Controller@cpay')->name('cpay');
        // Crypto swap routes (module-gated)
        Route::middleware('check.module:cryptoswap')->group(function () {
            Route::get('asset-balance', [ExchangeController::class, 'assetview'])->name('assetbalance');
            Route::get('swap-history', [ExchangeController::class, 'history'])->name('swaphistory');
            Route::get('asset-price/{base}/{quote}/{amount}', [ExchangeController::class, 'getprice'])->name('getprice');
            Route::post('exchange', [ExchangeController::class, 'exchange'])->name('exchangenow');
            Route::get('balances/{coin}', [ExchangeController::class, 'getBalance'])->name('getbalance');
        });

        // USer to User transfer
        Route::post('transfertouser', [TransferController::class, 'transfertouser'])->name('transfertouser');

        // binance crypto payments routes
        Route::get('/binance/success', [ViewsController::class, 'binanceSuccess'])->name('bsuccess');
        Route::get('/binance/error', [ViewsController::class, 'binanceError'])->name('berror');


        // Membership / Education routes (module-gated)
        Route::middleware('check.module:membership')->name('user.')->group(function () {
            Route::get('/courses', [MembershipController::class, 'courses'])->name('courses');
            Route::get('/course-details/{course}/{id}', [MembershipController::class, 'courseDetails'])->name('course.details');
            Route::post('/buy-course', [MembershipController::class, 'buyCourse'])->name('buycourse');
            Route::get('/my-courses', [MembershipController::class, 'myCourses'])->name('mycourses');
            Route::get('/course-details/{id}', [MembershipController::class, 'myCoursesDetails'])->name('mycoursedetails');
            Route::get('/learning/{lesson}/{course?}', [MembershipController::class, 'learning'])->name('learning');
        });



        // NFT routes (module-gated)
        Route::middleware('check.module:nft')->group(function () {
            Route::get('/nft-gallery', [UserNFTController::class, 'gallery'])->name('nft.gallery');
            Route::get('/nfts/create', [UserNFTController::class, 'create'])->name('user.nfts.create');
            Route::post('/nfts/store', [UserNFTController::class, 'store'])->name('user.nfts.store');
            Route::get('/nfts/collection/{collection}', [UserNFTController::class, 'collection'])->name('user.nfts.collection');
            Route::get('/nfts/{nft}', [UserNFTController::class, 'show'])->name('user.nfts.show');
            Route::get('/my-nfts', [UserNFTController::class, 'myNFTs'])->name('user.nfts.my');
            Route::post('/nfts/{nft}/like', [UserNFTController::class, 'toggleLike'])->name('user.nfts.like');
        });


     //signal subscription
    //  Route::post('/subscribe-signal/{plan}', [UserSubscriptionController::class, 'subscribe'])->name('user.signal.subscribe');
    //  Route::get('/my-subscriptions',          [UserSubscriptionController::class, 'index'])->name('user.signal.subscriptions');

     // Signal subscription routes (module-gated)
     Route::middleware('check.module:signal')->group(function () {
         Route::get('/subscribe-signals', [UserSubscriptionController::class, 'showPlans'])->name('user.signal.plans');
         Route::post('/subscribe', [UserSubscriptionController::class, 'subscribe'])->name('user.signal.subscribe');
         Route::get('/my-subscriptions', [UserSubscriptionController::class, 'mySubscriptions'])->name('user.signal.subscriptions');
         Route::get('/singalssubscriptions', [UserSubscriptionController::class, 'index'])->name('user.signal.index');
     });
    //nft biding

     // NFT bidding/buying/selling (module-gated)
     Route::middleware('check.module:nft')->group(function () {
         Route::post('/nfts/{nft}/bid', [BidController::class, 'placeBid'])->name('bids.place');
         Route::post('/nfts/{nft}/buy', [BuyController::class, 'buyNFT'])->name('nfts.buy');
         Route::post('/nfts/{nft}/sell', [UserNFTController::class, 'sellNFT'])->name('user.nfts.sell');
     });


        // Copy trading routes (module-gated)
        Route::middleware('check.module:copy_trading')->group(function () {
            Route::get('copy-trading', [CopyTradingController::class, 'index'])->name('copyTrading');
            Route::get('copy-trading/expert/{expert}', [CopyTradingController::class, 'showExpert'])->name('copyTrading.expert');
            Route::post('copy-trading/start/{expert}', [CopyTradingController::class, 'startCopying'])->name('copyTrading.start');
            Route::post('copy-trading/stop/{position}', [CopyTradingController::class, 'stopCopying'])->name('copyTrading.stop');
            Route::get('copy-trading/position/{position}', [CopyTradingController::class, 'showPosition'])->name('copyTrading.position');
        });

        // Bot trading routes (module-gated)
        Route::middleware('check.module:bot_trading')->group(function () {
            Route::get('bot-trading', [BotTradingController::class, 'index'])->name('botTrading');
            Route::get('bot-trading/bot/{bot}', [BotTradingController::class, 'showBot'])->name('botTrading.bot');
            Route::post('bot-trading/subscribe', [BotTradingController::class, 'subscribe'])->name('botTrading.subscribe');
            Route::post('bot-trading/stop/{subscription}', [BotTradingController::class, 'stopSubscription'])->name('botTrading.stop');
            Route::get('bot-trading/subscription/{subscription}', [BotTradingController::class, 'showSubscription'])->name('botTrading.subscription');
        });

        // Loan routes (module-gated)
        Route::middleware('check.module:loan')->group(function () {
            Route::get('/loans/apply', [LoanController::class, 'create'])->name('loans.create');
            Route::post('/loans/store', [LoanController::class, 'store'])->name('loans.store');
            Route::get('/my-loans', [LoanController::class, 'index'])->name('loans.my');
            Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
            Route::post('/loans/{loan}/repay', [LoanController::class, 'repay'])->name('loans.repay');
            Route::post('/loans/{loan}/repay-deposit', [LoanController::class, 'repayViaDeposit'])->name('loans.repay-deposit');
            Route::post('/loans/calculate-preview', [LoanController::class, 'calculatePreview'])->name('loans.calculate');
        });

        // Pre-IPO Shares (module-gated)
        Route::middleware('check.module:pre_ipo')->group(function () {
            Route::get('/pre-ipo', [UserPreIpoController::class, 'index'])->name('user.pre-ipo.index');
            Route::get('/pre-ipo/holdings', [UserPreIpoController::class, 'holdings'])->name('user.pre-ipo.holdings');
            Route::get('/pre-ipo/{id}', [UserPreIpoController::class, 'show'])->name('user.pre-ipo.show');
            Route::post('/pre-ipo/{id}/buy', [UserPreIpoController::class, 'buy'])->name('user.pre-ipo.buy');
            Route::post('/pre-ipo/sell/{holding}', [UserPreIpoController::class, 'sell'])->name('user.pre-ipo.sell');
            Route::get('/pre-ipo/{id}/price-history', [UserPreIpoController::class, 'priceHistory'])->name('user.pre-ipo.price-history');
        });

        // Stock Shares Trading (module-gated)
        Route::middleware('check.module:stocktrading')->group(function () {
            Route::get('/stocks', [StockController::class, 'index'])->name('user.stocks.index');
            Route::get('/stocks/portfolio', [StockController::class, 'portfolio'])->name('user.stocks.portfolio');
            Route::get('/stocks/history', [StockController::class, 'history'])->name('user.stocks.history');
            Route::get('/stocks/{id}', [StockController::class, 'show'])->name('user.stocks.show');
            Route::post('/stocks/buy', [StockController::class, 'buy'])->name('user.stocks.buy');
            Route::post('/stocks/sell', [StockController::class, 'sell'])->name('user.stocks.sell');
        });

        // Signals (module-gated)
        Route::middleware('check.module:signal')->group(function () {
            Route::get('/trade-signals', function () {
                return redirect()->route('user.signal.index');
            })->name('tsignals');
            Route::post('/renew-subscription/{subscription}', [UserSubscriptionController::class, 'renew'])->name('renewsignals');
        });
    });
});
Route::post('sendcontact', 'App\Http\Controllers\User\UsersController@sendcontact')->name('enquiry');
Route::post('otherpayment', 'App\Http\Controllers\User\UsersController@otherpayment')->name('otherpayment');
