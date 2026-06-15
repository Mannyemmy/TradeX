<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreIpoCompany;
use App\Models\PreIpoHolding;
use App\Models\PreIpoPriceHistory;
use App\Models\TradingAsset;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Mail\PreIpoStatusMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PreIpoController extends Controller
{
    public function index()
    {
        $settings = Settings::find(1);
        $companies = PreIpoCompany::orderByDesc('created_at')->paginate(15);

        return view('admin.pre-ipo.index')->with([
            'title' => 'Pre-IPO Companies',
            'settings' => $settings,
            'companies' => $companies,
        ]);
    }

    public function create()
    {
        $settings = Settings::find(1);

        return view('admin.pre-ipo.create')->with([
            'title' => 'Add Pre-IPO Company',
            'settings' => $settings,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20|unique:pre_ipo_companies,symbol',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'sector' => 'nullable|string|max:100',
            'share_price' => 'required|numeric|min:0.01',
            'total_shares' => 'required|integer|min:1',
            'min_shares' => 'required|integer|min:1',
            'max_shares_per_user' => 'nullable|integer|min:1',
            'expected_ipo_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'name', 'symbol', 'description', 'sector', 'share_price',
            'total_shares', 'min_shares', 'max_shares_per_user', 'expected_ipo_date',
        ]);

        $data['symbol'] = strtoupper($data['symbol']);
        $data['initial_price'] = $request->share_price;
        $data['is_featured'] = $request->has('is_featured');
        $data['status'] = 'upcoming';

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('pre-ipo', 'public');
        }

        $company = PreIpoCompany::create($data);

        // Record initial price in history
        PreIpoPriceHistory::create([
            'pre_ipo_company_id' => $company->id,
            'price' => $company->share_price,
            'changed_by' => Auth::guard('admin')->id(),
            'note' => 'Initial listing price',
            'created_at' => now(),
        ]);

        return redirect()->route('admin.pre-ipo.index')->with('success', 'Pre-IPO company created successfully.');
    }

    public function show($id)
    {
        $settings = Settings::find(1);
        $company = PreIpoCompany::findOrFail($id);
        $holdings = PreIpoHolding::where('pre_ipo_company_id', $id)
            ->with('user')
            ->paginate(15);
        $priceHistory = PreIpoPriceHistory::where('pre_ipo_company_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pre-ipo.show')->with([
            'title' => 'Pre-IPO: ' . $company->name,
            'settings' => $settings,
            'company' => $company,
            'holdings' => $holdings,
            'priceHistory' => $priceHistory,
        ]);
    }

    public function edit($id)
    {
        $settings = Settings::find(1);
        $company = PreIpoCompany::findOrFail($id);
        $priceHistory = PreIpoPriceHistory::where('pre_ipo_company_id', $id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();
        $tradingAssets = TradingAsset::active()->orderBy('name')->get();

        return view('admin.pre-ipo.edit')->with([
            'title' => 'Edit: ' . $company->name,
            'settings' => $settings,
            'company' => $company,
            'priceHistory' => $priceHistory,
            'tradingAssets' => $tradingAssets,
        ]);
    }

    public function update(Request $request, $id)
    {
        $company = PreIpoCompany::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20|unique:pre_ipo_companies,symbol,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'sector' => 'nullable|string|max:100',
            'share_price' => 'required|numeric|min:0.01',
            'total_shares' => 'required|integer|min:1',
            'min_shares' => 'required|integer|min:1',
            'max_shares_per_user' => 'nullable|integer|min:1',
            'expected_ipo_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'trading_asset_id' => 'nullable|exists:trading_assets,id',
            'created_at' => 'nullable|date',
        ]);

        $oldPrice = $company->share_price;

        $company->name = $request->name;
        $company->symbol = strtoupper($request->symbol);
        $company->description = $request->description;
        $company->sector = $request->sector;
        $company->share_price = $request->share_price;
        $company->total_shares = $request->total_shares;
        $company->min_shares = $request->min_shares;
        $company->max_shares_per_user = $request->max_shares_per_user;
        $company->expected_ipo_date = $request->expected_ipo_date;
        $company->is_featured = $request->has('is_featured');
        $company->trading_asset_id = $request->trading_asset_id;

        if ($request->hasFile('logo')) {
            $company->logo = $request->file('logo')->store('pre-ipo', 'public');
        }

        $company->save();

        if ($request->filled('created_at')) {
            DB::table('pre_ipo_companies')->where('id', $company->id)
                ->update(['created_at' => Carbon::parse($request->created_at), 'updated_at' => $company->updated_at]);
        }

        // If price changed, record in history
        if ($oldPrice != $request->share_price) {
            PreIpoPriceHistory::create([
                'pre_ipo_company_id' => $company->id,
                'price' => $company->share_price,
                'changed_by' => Auth::guard('admin')->id(),
                'note' => $request->price_note,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('admin.pre-ipo.edit', $company->id)->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = PreIpoCompany::findOrFail($id);

        if ($company->shares_sold > 0) {
            return redirect()->back()->with('message', 'Cannot delete a company with shares already sold.');
        }

        $company->delete();

        return redirect()->route('admin.pre-ipo.index')->with('success', 'Company deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $company = PreIpoCompany::findOrFail($id);
        $newStatus = $request->input('status');

        $allowed = [
            'upcoming' => 'open',
            'open' => 'closed',
            'closed' => 'ipo',
            'ipo' => 'public',
        ];

        if (!isset($allowed[$company->status]) || $allowed[$company->status] !== $newStatus) {
            return redirect()->back()->with('message', 'Invalid status transition.');
        }

        if ($newStatus === 'public' && !$company->trading_asset_id) {
            return redirect()->back()->with('message', 'A Trading Asset must be linked before going public.');
        }

        DB::transaction(function () use ($company, $newStatus) {
            if ($newStatus === 'open') {
                $company->opened_at = now();
            } elseif ($newStatus === 'closed') {
                $company->closed_at = now();
            } elseif ($newStatus === 'public') {
                $company->went_public_at = now();
                // Convert all holdings
                PreIpoHolding::where('pre_ipo_company_id', $company->id)
                    ->where('status', 'active')
                    ->update([
                        'status' => 'converted',
                        'converted_at' => now(),
                    ]);
            }

            $company->status = $newStatus;
            $company->save();
        });

        // Notify all holders of the status change when company goes public
        if ($newStatus === 'public') {
            $settings = Settings::find(1);
            if ($settings->trade_notification ?? false) {
                $holders = PreIpoHolding::where('pre_ipo_company_id', $company->id)->with('user')->get();
                foreach ($holders as $holding) {
                    if ($holding->user && $holding->user->email) {
                        try {
                            Mail::to($holding->user->email)->send(new PreIpoStatusMail($holding->user, $company, $newStatus));
                        } catch (\Exception $e) {
                            // Continue with other holders
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Status updated to ' . $newStatus . '.');
    }

    public function updatePrice(Request $request, $id)
    {
        $company = PreIpoCompany::findOrFail($id);

        $request->validate([
            'share_price' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        $company->share_price = $request->share_price;
        $company->save();

        PreIpoPriceHistory::create([
            'pre_ipo_company_id' => $company->id,
            'price' => $request->share_price,
            'changed_by' => Auth::guard('admin')->id(),
            'note' => $request->note,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Price updated to $' . number_format($request->share_price, 2));
    }

    public function holdings($id)
    {
        $settings = Settings::find(1);
        $company = PreIpoCompany::findOrFail($id);
        $holdings = PreIpoHolding::where('pre_ipo_company_id', $id)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.pre-ipo.holdings')->with([
            'title' => 'Holdings: ' . $company->name,
            'settings' => $settings,
            'company' => $company,
            'holdings' => $holdings,
        ]);
    }

    public function allHoldings()
    {
        $settings = Settings::find(1);
        $holdings = PreIpoHolding::with(['user', 'company'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.pre-ipo.holdings')->with([
            'title' => 'All Pre-IPO Holdings',
            'settings' => $settings,
            'company' => null,
            'holdings' => $holdings,
        ]);
    }

    public function priceHistoryApi($id)
    {
        $history = PreIpoPriceHistory::where('pre_ipo_company_id', $id)
            ->orderBy('created_at')
            ->get(['price', 'created_at']);

        return response()->json($history);
    }
}
