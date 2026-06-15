<?php

namespace App\Mail;

use App\Models\PreIpoCompany;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreIpoStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $company, $newStatus, $settings, $subject;

    public function __construct(User $user, PreIpoCompany $company, string $newStatus, $subject = null)
    {
        $this->user = $user;
        $this->company = $company;
        $this->newStatus = $newStatus;
        $this->settings = Settings::find(1);
        $this->subject = $subject ?? $company->name . ' — Status Update: ' . ucfirst($newStatus);
    }

    public function build()
    {
        return $this->view('emails.pre_ipo_status')->subject($this->subject);
    }
}
