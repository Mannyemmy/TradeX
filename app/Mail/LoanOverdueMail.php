<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;
use App\Models\User;

class LoanOverdueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan, $user, $overdueCount, $subject;

    public function __construct(Loan $loan, User $user, int $overdueCount, $subject)
    {
        $this->loan = $loan;
        $this->user = $user;
        $this->overdueCount = $overdueCount;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->view('emails.loan_overdue')->subject($this->subject);
    }
}
