<?php

namespace App\Mail;

use App\Models\PreIpoCompany;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreIpoPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $company, $quantity, $totalCost, $settings, $subject;

    public function __construct(User $user, PreIpoCompany $company, int $quantity, float $totalCost, $subject = 'Pre-IPO Share Purchase Confirmation')
    {
        $this->user = $user;
        $this->company = $company;
        $this->quantity = $quantity;
        $this->totalCost = $totalCost;
        $this->settings = Settings::find(1);
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->view('emails.pre_ipo_purchase')->subject($this->subject);
    }
}
