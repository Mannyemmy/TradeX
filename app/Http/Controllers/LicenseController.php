<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\License;
use Illuminate\Support\Facades\Cache;

class LicenseController extends Controller
{
    public function showRequestForm()
    {
        return view('license.request');
    }

    public function validatePurchaseCode(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string|min:8',
        ]);

        $validCode = env('LICENSE_PURCHASE_CODE');
        if (empty($validCode) || $request->purchase_code !== $validCode) {
            return back()->with('error', 'Invalid Purchase Code');
        }

        License::whereNull('purchase_code')->update(['purchase_code' => $request->purchase_code]);

        Cache::forget('license_checked'); // Reset cache
        return redirect('/')->with('success', 'License Activated!');
    }
}
