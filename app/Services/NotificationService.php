<?php

namespace App\Services;

use App\Models\User;
use App\Models\Admin;
use App\Notifications\UserNotification;

class NotificationService
{
    /**
     * Send an in-app (database) notification to a user.
     *
     * @param User        $user
     * @param string      $type      Category key (deposit, withdrawal, trade, loan, etc.)
     * @param string      $title     Short heading
     * @param string      $message   Body text
     * @param string|null $actionUrl Link target
     * @param string|null $icon      Icon component name
     */
    public static function notifyUser(User $user, string $type, string $title, string $message, ?string $actionUrl = null, ?string $icon = null): void
    {
        $user->notify(new UserNotification($type, $title, $message, $icon, $actionUrl));
    }

    /**
     * Send an in-app (database) notification to all admins.
     */
    public static function notifyAdmin(string $type, string $title, string $message, ?string $actionUrl = null): void
    {
        foreach (Admin::all() as $admin) {
            $admin->notify(new UserNotification($type, $title, $message, null, $actionUrl));
        }
    }
}
