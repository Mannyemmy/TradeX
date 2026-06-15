<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\SettingsCont;
use App\Services\CoinGeckoService;
use App\Services\TwelveDataService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{

    // Return view
    public function appsettingshow()
    {
        $live_timezones = timezone_identifiers_list();
        include 'currencies.php';
        return view('admin.Settings.AppSettings.show', [
            'title' => 'Website information settings',
            'timezones' => $live_timezones,
            'currencies' => $currencies,
            'timezone' => config('app.timezone'),
            'settings' => Settings::where('id', '=', '1')->first(),
            'settingsCont' => SettingsCont::find(1),
        ]);
    }

    public function updateTheme(Request $request){
        $this->validate($request, [
            'theme' => 'mimes:zip|max:30000',
        ]);

        $file = $request->file('theme');

        $settings = Settings::find(1);

        if ($file->extension() != 'zip') {
            return redirect()->back()->with('message', 'Please upload a zip file');
        }

        // Sanitize theme name — allow only alphanumeric, dash, underscore
        $themeName = preg_replace('/[^a-zA-Z0-9_\-]/', '', substr($file->getClientOriginalName(), 0, -4));
        if (empty($themeName)) {
            return redirect()->back()->with('message', 'Invalid theme name.');
        }

        $zip = new \ZipArchive();
        $open = $zip->open($file->getRealPath());

        if ($open === TRUE) {
            // Validate every outer zip entry for path traversal before extracting
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                if (strpos($entry, '..') !== false || strpos($entry, "\0") !== false) {
                    $zip->close();
                    return redirect()->back()->with('message', 'Invalid zip: path traversal detected.');
                }
            }

            // extract the zip file to the themes folder
            $zip->extractTo(base_path("themes/{$themeName}"));
            $zip->close();

            // Safely extract the nested views.zip — only allow .blade.php files
            $viewsZipPath = base_path("themes/{$themeName}/views.zip");
            if (!file_exists($viewsZipPath)) {
                return redirect()->back()->with('message', 'Theme is missing views.zip.');
            }

            $viewsZip = new \ZipArchive();
            if ($viewsZip->open($viewsZipPath) === TRUE) {
                for ($i = 0; $i < $viewsZip->numFiles; $i++) {
                    $entry = $viewsZip->getNameIndex($i);
                    if (strpos($entry, '..') !== false || strpos($entry, "\0") !== false) {
                        $viewsZip->close();
                        return redirect()->back()->with('message', 'Invalid views.zip: path traversal detected.');
                    }
                    // Reject any non-Blade file (skip directory entries ending in /)
                    if (!str_ends_with($entry, '/') && !str_ends_with($entry, '.blade.php')) {
                        $viewsZip->close();
                        return redirect()->back()->with('message', 'Invalid views.zip: only .blade.php files are permitted.');
                    }
                }
                $viewsZip->extractTo(resource_path('views'));
                $viewsZip->close();
            }

            // add the theme to the database
            $settings->theme = $themeName;
            $settings->save();
            $this->clearCache();
            return redirect()->back()->with('success', 'Theme uploaded successfully.');
        }

        return redirect()->back()->with('message', 'There was an error uploading the theme, please try again.');

    }

    public function clearCache()
    {
         //clear the cache with Artisan command
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
    }

    // update wensite information
    public function updatewebinfo(Request $request)
    {
        $this->validate($request, [
            'logo' => 'mimes:jpg,jpeg,png|max:5120|image',
            'favicon' => 'mimes:jpg,jpeg,png,ico|max:5120',
        ]);

        $settings = Settings::where('id', '=', '1')->first();

        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            Storage::disk('public')->delete($settings->logo);
            $path = $file->store('photos', 'public');
        } else {
            $path  = $settings->logo;
        }

        if ($request->hasfile('favicon')) {
            $favfile = $request->file('favicon');
            Storage::disk('public')->delete($settings->favicon);
            $pathfav = $favfile->store('photos', 'public');
        } else {
            $pathfav = $settings->favicon;
        }



        Settings::where('id', '1')
            ->update([
                'newupdate' => $request['update'],
                'site_name' => $request['site_name'],
                'description' => $request['description'],
                'keywords' => $request['keywords'],
                'timezone' => $request['timezone'],
                'site_title' => $request['site_title'],
                'install_type' => $request['install_type'],
                'logo' => $path,
                'merchant_key' => $request->merchant_key,
                'favicon' => $pathfav,
                'tawk_to' => strip_tags($request['tawk_to']),
                'site_address' => $request['site_address'],
                'welcome_message' => $request->welcome_message,
                'whatsapp' => $request->whatsapp,
                'gasfee' => $request->gasfee,
                'trade_message' => $request->trade_message,

            ]);

        $moreset = SettingsCont::find(1);
        $moreset->purchase_code = $request->purchase_code;
        $moreset->save();

        return redirect()->back()->with('success', 'Settings Saved successfully.');
    }



    public function updatepreference(Request $request)
    {

        if ($request->return_capital == 'true') {
            $return_capital = true;
        } else {
            $return_capital = false;
        }

        Settings::where('id', 1)->update([
            'contact_email' => $request['contact_email'],
            'currency' => $request['currency'],
            's_currency' => $request['s_currency'],
            'weekend_trade' => $request['weekend_trade'],
            'location' => $request['location'],
            'trade_mode' => $request['trade_mode'],
            'enable_verification' => $request['enail_verify'],
            'google_translate' => $request['googlet'],
            'enable_kyc' => $request['enable_kyc'],
            'enable_kyc_registration' => $request['enable_kyc_registration'],
            'captcha' => $request['captcha'],
            'enable_with' => $request['withdraw'],
            'return_capital' => $return_capital,
            'enable_social_login' => $request['social'],
            'enable_annoc' => $request['annouc'],
            'redirect_url' => $request->redirect_url,
            'should_cancel_plan' => $request->should_cancel_plan,
        ]);
        return response()->json(['status' => 200, 'success' => 'Settings Saved successfully .']);
    }

    // Update email preference
    public function updateemail(Request $request)
    {
        Settings::where('id', ' 1')
            ->update([
                'mail_server' => $request['server'],
                'emailfrom' => $request['emailfrom'],
                'emailfromname' => $request['emailfromname'],
                'smtp_host' => $request['smtp_host'],
                'smtp_port' => $request['smtp_port'],
                'smtp_encrypt' => $request['smtp_encrypt'],
                'smtp_user' => $request['smtp_user'],
                'smtp_password' => $request['smtp_password'],
                'google_id' => $request['google_id'],
                'google_secret' => $request['google_secret'],
                'google_redirect' => $request['google_redirect'],
                'capt_secret' => $request['capt_secret'],
                'capt_sitekey' => $request['capt_sitekey'],
            ]);
        return response()->json(['status' => 200, 'success' => 'Settings Saved successfully .']);
        //return redirect()->back()->with('message', 'Action Sucessful');
    }

    /**
     * Save API keys for CoinGecko and TwelveData.
     */
    public function updateApiKeys(Request $request)
    {
        $request->validate([
            'coingecko_api_key' => 'nullable|string|max:255',
            'twelvedata_api_key' => 'nullable|string|max:255',
        ]);

        $settings = SettingsCont::find(1);
        $settings->coingecko_api_key = $request->coingecko_api_key;
        $settings->twelvedata_api_key = $request->twelvedata_api_key;
        $settings->save();

        return redirect()->back()->with('success', 'API keys saved successfully.');
    }

    /**
     * Test API connection (AJAX).
     */
    public function toggleModule(Request $request)
    {
        $allowed = [
            'investment', 'cryptoswap', 'pre_ipo', 'trading',
            'copy_trading', 'bot_trading', 'signal', 'nft', 'loan', 'membership',
            'stocktrading',
        ];

        $module = $request->input('module');
        $value = $request->input('value');

        if (!in_array($module, $allowed)) {
            return redirect()->back()->with('message', 'Invalid module');
        }

        $settings = Settings::find(1);
        $options = $settings->modules ?? [];
        $options[$module] = $value === 'true';
        $settings->modules = $options;
        $settings->save();

        return redirect()->back()->with('success', 'Module updated successfully');
    }

    public function testApiConnection(Request $request)
    {
        $provider = $request->input('provider');

        if ($provider === 'coingecko') {
            $service = new CoinGeckoService();
            $result = $service->testConnection();
        } elseif ($provider === 'twelvedata') {
            $service = new TwelveDataService();
            $result = $service->testConnection();
        } else {
            return response()->json(['success' => false, 'message' => 'Unknown provider.']);
        }

        if ($result === true) {
            return response()->json(['success' => true, 'message' => 'Connection successful!']);
        }

        return response()->json(['success' => false, 'message' => $result]);
    }
}
