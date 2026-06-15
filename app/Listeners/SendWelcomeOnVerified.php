<?php

namespace App\Listeners;

use App\Jobs\SendWelcomeEmail;
use Illuminate\Auth\Events\Verified;

class SendWelcomeOnVerified
{
    public function handle(Verified $event): void
    {
        SendWelcomeEmail::dispatch($event->user);
    }
}
