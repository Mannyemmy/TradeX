<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use App\Models\User;

class LoanRepaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan, $user, $schedule, $subject;

    public function __construct(Loan $loan, User $user, LoanRepaymentSchedule $schedule, $subject)
    {
        $this->loan = $loan;
        $this->user = $user;
        $this->schedule = $schedule;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->view('emails.loan_repayment')->subject($this->subject);
    }
}
