<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $title;
    protected $message;
    protected $icon;
    protected $actionUrl;

    /**
     * @param string      $type      Category key (deposit, withdrawal, trade, loan, etc.)
     * @param string      $title     Short heading shown in bell dropdown
     * @param string      $message   Longer description shown in notification list
     * @param string|null $icon      Icon name for <x-icon> component
     * @param string|null $actionUrl URL the notification links to
     */
    public function __construct(string $type, string $title, string $message, ?string $icon = null, ?string $actionUrl = null)
    {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon ?? $this->defaultIcon($type);
        $this->actionUrl = $actionUrl;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'       => $this->type,
            'title'      => $this->title,
            'message'    => $this->message,
            'icon'       => $this->icon,
            'action_url' => $this->actionUrl,
        ];
    }

    private function defaultIcon(string $type): string
    {
        $icons = [
            'deposit'      => 'arrow-down-tray',
            'withdrawal'   => 'arrow-up-tray',
            'trade'        => 'chart-bar',
            'investment'   => 'banknotes',
            'loan'         => 'hand-raised',
            'copy_trading' => 'copy',
            'kyc'          => 'shield-check',
            'referral'     => 'users',
            'transfer'     => 'arrow-right-on-rectangle',
            'signal'       => 'signal',
            'nft'          => 'gem',
            'pre_ipo'      => 'building-office',
            'account'      => 'user-circle',
            'support'      => 'chat-bubble-left-right',
            'admin'        => 'bell',
        ];

        return $icons[$type] ?? 'bell';
    }
}
