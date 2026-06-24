<?php

use App\Http\Controllers\Admin\ClearCacheController;
use Illuminate\Support\Facades\Route;
use App\Models\Settings;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\AutoTaskController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\AssistantController;
use App\Jobs\CalculateCopyTradeProfit;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/admin/web.php';
require __DIR__ . '/user/web.php';
require __DIR__ . '/botman.php';

// ─── WealthWise Assistant (public: works for guests + logged-in users) ───
Route::prefix('assistant')->middleware('throttle:40,1')->group(function () {
    Route::post('message', [AssistantController::class, 'message'])->name('assistant.message');
    Route::post('escalate', [AssistantController::class, 'escalate'])->name('assistant.escalate');
    Route::get('poll', [AssistantController::class, 'poll'])->name('assistant.poll');
});

// ─── All-in-one cron endpoint for cPanel / wget / curl ─────────────
// Set CRON_SECRET in your .env to a long random string, then call:
// wget -q -O /dev/null "https://yourdomain.com/allcron?token=YOUR_SECRET"
Route::get('/allcron', function (\Illuminate\Http\Request $request) {
    $token = env('CRON_SECRET');
    if (empty($token) || $request->query('token') !== $token) {
        abort(403, 'Invalid token');
    }

    set_time_limit(600);
    $log = [];

    

    // 4. Copy trading profit calculation
    dispatch_sync(new \App\Jobs\CalculateCopyTradeProfit());
    $log[] = '✓ Copy trade profits calculated';

    // 5. Generate simulated trades for copy trading
    dispatch_sync(new \App\Jobs\GenerateSimulatedTrades());
    $log[] = '✓ Simulated trades generated';

    // 6b. Bot trading: generate simulated trades
    dispatch_sync(new \App\Jobs\GenerateBotTrades());
    $log[] = '✓ Bot trades generated';

    // 6c. Bot trading: calculate profit & auto-settle expired
    dispatch_sync(new \App\Jobs\CalculateBotProfit());
    $log[] = '✓ Bot profits calculated';

    // 6. Auto top-up / investment returns
    app(\App\Http\Controllers\AutoTaskController::class)->autotopup();
    $log[] = '✓ Auto top-up / ROI processed';

    // 7. Loan checks (due dates, penalties)
    app(\App\Http\Controllers\LoanCheckController::class)->run();
    $log[] = '✓ Loan checks completed';


    // 1. Process expired trades (binary option results)
    \Artisan::call('process:trades');
    $log[] = '✓ Trades processed';

    // 2. Update crypto prices
    \Artisan::call('prices:crypto');
    $log[] = '✓ Crypto prices updated';

    // 3. Update market prices (forex, stocks, ETFs, indices)
    \Artisan::call('prices:market');
    $log[] = '✓ Market prices updated';

    // 4. Update exchange rates (daily, skips if already fresh)
    $exchangeService = new \App\Services\ExchangeRateService();
    $rateResult = $exchangeService->updateRatesIfStale();
    if (isset($rateResult['skipped'])) {
        $log[] = '⊘ Exchange rates still fresh, skipped';
    } else {
        $log[] = "✓ Exchange rates updated ({$rateResult['updated']} currencies)";
    }

    return 'All cron tasks completed at ' . now()->toDateTimeString() . "\n" . implode("\n", $log);
});

//Front Pages Route
Route::get('/', [HomePageController::class, 'index'])->name('home');
Route::get('terms', [HomePageController::class, 'terms'])->name('terms');
Route::get('privacy', [HomePageController::class, 'privacy'])->name('privacy');
Route::get('about', [HomePageController::class, 'about'])->name('about');
Route::get('contact', [HomePageController::class, 'contact'])->name('contact');
// Route::get('/captcha', [CaptchaController::class, 'generateCaptcha']);

Route::get('/legal-docs', [HomePageController::class, 'faq'])->name('faq');



Route::get('markets', [HomePageController::class, 'pricing'])->name('pricing');
Route::get('licences', [HomePageController::class, 'licences'])->name('licences');
Route::get('risk', [HomePageController::class, 'risk'])->name('risk');
Route::get('security', [HomePageController::class, 'safety'])->name('safety');
Route::get('careers', [HomePageController::class, 'service'])->name('service');
Route::get('webtrade', [HomePageController::class, 'trading'])->name('trading');
