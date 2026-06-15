<?php

namespace App\Providers;

use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;
use App\Models\SettingsCont;
use App\Models\ThemeColor;
use App\Models\TermsPrivacy;
use App\Models\ExchangeRate;
use App\Helpers\CurrencyHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FacadesStorage::extend('sftp', function ($app, $config) {
            return new Filesystem(new SftpAdapter($config));
        });

        Paginator::useBootstrap();

        // Sharing settings with all views. Guarded so the application can still
        // boot (e.g. during `artisan migrate` or before the DB is seeded) when
        // the settings tables don't yet exist or are empty.
        $settings = null;
        $terms = null;
        $moreset = null;
        $themeColors = null;

        try {
            $settings = Settings::where('id', '1')->first();
            $terms =  TermsPrivacy::find(1);
            $moreset =  SettingsCont::find(1);
            $themeColors = ThemeColor::find(1);
        } catch (\Throwable $e) {
            // Database not ready / not yet migrated — fall back to nulls.
        }

        View::share('settings', $settings);
        View::share('terms', $terms);
        View::share('moresettings', $moreset);
        View::share('mod', $settings ? $settings->modules : null);
        View::share('themeColors', $themeColors);

        // Blade directive: @money($amount) — formats USD amount in user's currency
        Blade::directive('money', function ($expression) {
            return "<?php echo \App\Helpers\CurrencyHelper::formatForUser($expression); ?>";
        });

        // Blade directive: @userCurrency — outputs user's currency symbol
        Blade::directive('userCurrency', function () {
            return "<?php echo \App\Helpers\CurrencyHelper::getUserSymbol(); ?>";
        });

        // Blade directive: @userCurrencyCode — outputs user's currency code
        Blade::directive('userCurrencyCode', function () {
            return "<?php echo \App\Helpers\CurrencyHelper::getUserCode(); ?>";
        });

        // Share user currency data with all user.* views
        View::composer('user.*', function ($view) {
            $user = Auth::user();
            if ($user) {
                $view->with('userCurrencySymbol', CurrencyHelper::getUserSymbol($user));
                $view->with('userCurrencyCode', CurrencyHelper::getUserCode($user));
                $view->with('userCurrencyRate', CurrencyHelper::getUserRate($user));
            }
        });

        // Inject admin unread notification count into all admin views
        View::composer('admin.*', function ($view) {
            $admin = Auth::guard('admin')->user();
            $view->with('adminUnreadNotifCount', $admin ? $admin->unreadNotifications()->count() : 0);
        });
    }
}