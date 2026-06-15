<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;
use App\Models\User;

class LoanCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan, $user, $subject;

    public function __construct(Loan $loan, User $user, $subject)
    {
        $this->loan = $loan;
        $this->user = $user;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->view('emails.loan_completed')->subject($this->subject);
    }
}
