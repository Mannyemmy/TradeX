<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $rates = ExchangeRate::when($search, function ($query) use ($search) {
                $query->where('currency_code', 'like', "%{$search}%")
                      ->orWhere('currency_name', 'like', "%{$search}%");
            })
            ->orderBy('currency_code')
            ->paginate(20);

        return view('admin.Settings.ExchangeRates.index')->with([
            'title' => 'Exchange Rates',
            'rates' => $rates,
            'search' => $search,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rate_to_usd' => 'required|numeric|gt:0',
        ]);

        $rate = ExchangeRate::findOrFail($id);
        $rate->rate_to_usd = $request->rate_to_usd;
        $rate->is_manual = true;
        $rate->save();

        ExchangeRate::clearCache($rate->currency_code);

        return response()->json([
            'status' => 200,
            'success' => "Rate for {$rate->currency_code} updated to {$rate->rate_to_usd}",
        ]);
    }

    public function toggleActive(Request $request, $id)
    {
        $rate = ExchangeRate::findOrFail($id);
        $rate->is_active = !$rate->is_active;
        $rate->save();

        ExchangeRate::clearCache($rate->currency_code);

        $status = $rate->is_active ? 'enabled' : 'disabled';
        return response()->json([
            'status' => 200,
            'success' => "{$rate->currency_code} has been {$status}",
        ]);
    }

    public function fetchRates()
    {
        $service = new ExchangeRateService();
        $result = $service->updateRates();

        if (!empty($result['errors'])) {
            return response()->json([
                'status' => 422,
                'message' => 'Some errors occurred: ' . implode(', ', $result['errors']),
                'updated' => $result['updated'],
            ]);
        }

        return response()->json([
            'status' => 200,
            'success' => "Successfully updated {$result['updated']} exchange rates from API",
        ]);
    }

    public function resetRate($id)
    {
        $rate = ExchangeRate::findOrFail($id);
        $rate->is_manual = false;
        $rate->save();

        // Immediately fetch the API rate for this currency
        $service = new ExchangeRateService();
        $result = $service->updateRates();

        ExchangeRate::clearCache($rate->currency_code);

        return response()->json([
            'status' => 200,
            'success' => "{$rate->currency_code} reset to API rate",
        ]);
    }
}
