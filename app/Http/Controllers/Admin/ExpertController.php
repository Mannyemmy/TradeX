<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;
use App\Models\CopyPosition;
use Illuminate\Support\Facades\Storage;

class ExpertController extends Controller
{
    public function index()
    {
        $experts = Expert::withCount(['positions as active_copiers_count' => function ($q) {
            $q->where('status', 'active');
        }])->paginate(15);

        $totalExperts = Expert::count();
        $activeExperts = Expert::where('is_active', true)->count();
        $totalActiveCopiers = CopyPosition::where('status', 'active')->count();

        $title = 'Manage Expert Traders';
        return view('admin.experts.index')->with([
            'title' => $title,
            'experts' => $experts,
            'totalExperts' => $totalExperts,
            'activeExperts' => $activeExperts,
            'totalActiveCopiers' => $totalActiveCopiers,
        ]);
    }

    public function create()
    {
        $title = 'Create New Expert';
        return view('admin.experts.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'area_of_expertise' => 'required|string',
            'daily_roi' => 'required|numeric|min:0.01|max:100',
            'duration_days' => 'required|integer|min:1',
            'win_rate' => 'required|numeric|min:0|max:100',
            'profit_share_percentage' => 'required|numeric|min:0|max:100',
            'min_startup_capital' => 'required|numeric|min:0',
            'max_capital' => 'nullable|numeric|min:0',
            'followers_count' => 'nullable|integer|min:0',
            'total_roi' => 'nullable|numeric|min:0',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'name', 'bio', 'area_of_expertise', 'daily_roi', 'duration_days',
            'win_rate', 'profit_share_percentage', 'min_startup_capital', 'max_capital',
            'followers_count', 'total_roi',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['followers_count'] = $data['followers_count'] ?? 0;
        $data['total_roi'] = $data['total_roi'] ?? 0;

        $expert = new Expert($data);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('experts', 'public');
            $expert->profile_picture = $path;
        }

        $expert->save();

        return redirect()->route('admin.experts.index')->with('success', 'Expert added successfully.');
    }

    public function show($id)
    {
        $expert = Expert::findOrFail($id);
        $positions = CopyPosition::where('expert_id', $id)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        $title = 'Expert: ' . $expert->name;
        return view('admin.experts.show')->with([
            'title' => $title,
            'expert' => $expert,
            'positions' => $positions,
        ]);
    }

    public function edit($id)
    {
        $expert = Expert::findOrFail($id);
        $title = 'Edit Expert: ' . $expert->name;
        return view('admin.experts.edit', compact('expert', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'area_of_expertise' => 'required|string',
            'daily_roi' => 'required|numeric|min:0.01|max:100',
            'duration_days' => 'required|integer|min:1',
            'win_rate' => 'required|numeric|min:0|max:100',
            'profit_share_percentage' => 'required|numeric|min:0|max:100',
            'min_startup_capital' => 'required|numeric|min:0',
            'max_capital' => 'nullable|numeric|min:0',
            'followers_count' => 'nullable|integer|min:0',
            'total_roi' => 'nullable|numeric|min:0',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $expert = Expert::findOrFail($id);

        $data = $request->only([
            'name', 'bio', 'area_of_expertise', 'daily_roi', 'duration_days',
            'win_rate', 'profit_share_percentage', 'min_startup_capital', 'max_capital',
            'followers_count', 'total_roi',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['followers_count'] = $data['followers_count'] ?? 0;
        $data['total_roi'] = $data['total_roi'] ?? 0;

        $expert->fill($data);

        if ($request->hasFile('profile_picture')) {
            if ($expert->profile_picture) {
                Storage::disk('public')->delete($expert->profile_picture);
            }
            $path = $request->file('profile_picture')->store('experts', 'public');
            $expert->profile_picture = $path;
        }

        $expert->save();

        return redirect()->route('admin.experts.index')->with('success', 'Expert updated successfully.');
    }

    public function destroy($id)
    {
        $expert = Expert::findOrFail($id);

        $activeCopies = CopyPosition::where('expert_id', $id)->where('status', 'active')->count();
        if ($activeCopies > 0) {
            return redirect()->back()->with('message', 'Cannot delete expert with ' . $activeCopies . ' active copy position(s). Stop them first.');
        }

        if ($expert->profile_picture) {
            Storage::disk('public')->delete($expert->profile_picture);
        }
        $expert->delete();

        return redirect()->route('admin.experts.index')->with('success', 'Expert deleted successfully.');
    }

    public function toggleActive($id)
    {
        $expert = Expert::findOrFail($id);
        $expert->is_active = !$expert->is_active;
        $expert->save();

        return response()->json([
            'success' => true,
            'is_active' => $expert->is_active,
        ]);
    }
}
