<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('user.notification')->with([
            'title' => 'Notifications',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Return unread notifications for the bell dropdown (AJAX).
     */
    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications()->latest()->take(5)->get();
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'count' => $count,
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'title'      => $n->data['title'] ?? '',
                    'message'    => $n->data['message'] ?? '',
                    'icon'       => $n->data['icon'] ?? 'bell',
                    'action_url' => $n->data['action_url'] ?? null,
                    'time'       => $n->created_at->diffForHumans(),
                ];
            }),
        ]);
    }
}
