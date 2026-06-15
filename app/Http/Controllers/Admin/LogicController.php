<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Handles admin "agents" management.
 *
 * This controller was missing from the distribution even though the admin
 * agents view (resources/views/admin/agents.blade.php) and routes reference it.
 * It implements the three actions those views expect: add, view and remove an
 * agent. The agents table columns are: agent (user id), total_refered,
 * total_activated, earnings.
 */
class LogicController extends Controller
{
    /**
     * Create an agent from an existing user.
     */
    public function addagent(Request $request)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
            'referred_users' => 'nullable|integer|min:0',
        ]);

        // Avoid duplicating an existing agent for the same user.
        $existing = Agent::where('agent', $request->user)->first();
        if ($existing) {
            return back()->with('message', 'This user is already an agent.');
        }

        Agent::create([
            'agent' => $request->user,
            'total_refered' => $request->input('referred_users', 0),
            'total_activated' => 0,
            'earnings' => 0,
        ]);

        return back()->with('success', 'Agent added successfully.');
    }

    /**
     * Show a single agent record together with the users they referred.
     */
    public function viewagent($agent)
    {
        return view('admin.viewagent')->with([
            'title' => 'Agent record',
            'agent' => User::where('id', $agent)->first(),
            'ag_r' => User::where('ref_by', $agent)->get(),
        ]);
    }

    /**
     * Remove an agent (by agents.id).
     */
    public function delagent($id)
    {
        $agent = Agent::find($id);
        if ($agent) {
            $agent->delete();
        }

        return back()->with('success', 'Agent removed successfully.');
    }
}
