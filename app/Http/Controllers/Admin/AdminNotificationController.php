<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    private function admin()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Full paginated notifications list.
     */
    public function index()
    {
        $notifications = $this->admin()->notifications()->paginate(20);
        $unreadCount   = $this->admin()->unreadNotifications()->count();

        return view('admin.notifications.index', [
            'title'         => 'Notifications',
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
        ]);
    }

    /**
     * AJAX — unread notifications for the bell dropdown.
     * Returns same JSON shape as User\NotificationController::unread().
     */
    public function unread()
    {
        $notifications = $this->admin()->unreadNotifications()->latest()->take(10)->get();
        $count         = $this->admin()->unreadNotifications()->count();

        return response()->json([
            'count'         => $count,
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'title'      => $n->data['title']      ?? '',
                    'message'    => $n->data['message']    ?? '',
                    'icon'       => $n->data['icon']       ?? 'bell',
                    'action_url' => $n->data['action_url'] ?? null,
                    'time'       => $n->created_at->diffForHumans(),
                    'type'       => $n->data['type']       ?? 'general',
                ];
            }),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($id)
    {
        $notification = $this->admin()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $this->admin()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $this->admin()->notifications()->where('id', $id)->delete();

        return response()->json(['success' => true]);
    }
}
