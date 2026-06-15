<?php

namespace App\Jobs;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        try {
            $settings = Settings::where('id', 1)->first();
            if ($settings && $settings->enable_verification == 'true') {
                $this->user->notify(new VerifyEmail);
            }
        } catch (\Exception $e) {
            Log::error('Verification email failed: ' . $e->getMessage());
        }
    }
}
