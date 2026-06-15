<?php

namespace App\Http\Controllers;

use App\Mail\LoanDefaultedMail;
use App\Mail\LoanOverdueMail;
use App\Mail\LoanPaymentReminderMail;
use App\Services\NotificationService;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Models\LoanRepaymentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoanCheckController extends Controller
{
    /**
     * Run all loan checks. Called via GET /run-loan-check (admin-only cron).
     *
     * Steps:
     * 1. Mark upcoming installments as "due" when due_date is today or past
     * 2. Mark "due" installments as "overdue" when past grace period
     * 3. Apply late fees to overdue installments
     * 4. Send payment reminders (3 days before due)
     * 5. Send overdue notifications
     * 6. Auto-default loans with too many overdue installments (3+)
     */
    public function run()
    {
        $now = Carbon::now()->startOfDay();
        $results = [
            'marked_due' => 0,
            'marked_overdue' => 0,
            'late_fees_applied' => 0,
            'reminders_sent' => 0,
            'overdue_notices_sent' => 0,
            'auto_defaulted' => 0,
        ];

        // 1. Mark upcoming → due (due_date is today or past)
        $results['marked_due'] = LoanRepaymentSchedule::where('status', 'upcoming')
            ->where('due_date', '<=', $now)
            ->update(['status' => 'due']);

        // 2. Handle overdue: "due" installments past grace period
        $activeLoans = Loan::with(['loanPlan', 'user', 'repaymentSchedules'])
            ->whereIn('status', ['active', 'repaying'])
            ->get();

        foreach ($activeLoans as $loan) {
            $plan = $loan->loanPlan;
            $graceDays = $plan ? $plan->grace_period_days : 0;
            $lateFeePercent = $plan ? $plan->late_fee_percentage : 0;

            // Find "due" installments that are past grace period → mark overdue
            $newlyOverdue = $loan->repaymentSchedules()
                ->where('status', 'due')
                ->where('due_date', '<', $now->copy()->subDays($graceDays))
                ->get();

            foreach ($newlyOverdue as $schedule) {
                $schedule->update(['status' => 'overdue']);
                $results['marked_overdue']++;

                // 3. Apply late fee (only if not already applied)
                if ($lateFeePercent > 0 && $schedule->late_fee == 0) {
                    $lateFee = round($schedule->total_amount * ($lateFeePercent / 100), 2);
                    $schedule->update(['late_fee' => $lateFee]);
                    $results['late_fees_applied']++;
                }
            }

            // 4. Send payment reminders: upcoming installments due in 3 days
            $upcomingDue = $loan->repaymentSchedules()
                ->where('status', 'upcoming')
                ->whereDate('due_date', $now->copy()->addDays(3))
                ->first();

            if ($upcomingDue && $loan->user) {
                try {
                    Mail::to($loan->user->email)->send(
                        new LoanPaymentReminderMail($loan, $loan->user, $upcomingDue, 'Loan Payment Reminder')
                    );
                    NotificationService::notifyUser($loan->user, 'loan', 'Payment Reminder', 'Your loan installment of $' . number_format($upcomingDue->total_due, 2) . ' is due in 3 days.', url('dashboard/loans/' . $loan->id));
                    $results['reminders_sent']++;
                } catch (\Exception $e) {
                    Log::error('Loan reminder mail failed: ' . $e->getMessage());
                }
            }

            // 5. Send overdue notifications
            $overdueCount = $loan->repaymentSchedules()->where('status', 'overdue')->count();
            if ($overdueCount > 0 && $loan->user) {
                try {
                    Mail::to($loan->user->email)->send(
                        new LoanOverdueMail($loan, $loan->user, $overdueCount, 'Loan Payment Overdue')
                    );
                    NotificationService::notifyUser($loan->user, 'loan', 'Loan Payment Overdue', 'You have ' . $overdueCount . ' overdue loan installment(s). Please make payment immediately to avoid penalties.', url('dashboard/loans/' . $loan->id));
                    $results['overdue_notices_sent']++;
                } catch (\Exception $e) {
                    Log::error('Loan overdue mail failed: ' . $e->getMessage());
                }
            }

            // 6. Auto-default: 3+ overdue installments
            if ($overdueCount >= 3) {
                $loan->update(['status' => 'defaulted']);
                $loan->liquidateCollateral();
                $results['auto_defaulted']++;

                if ($loan->user) {
                    try {
                        Mail::to($loan->user->email)->send(
                            new LoanDefaultedMail($loan, $loan->user, 'Loan Marked as Defaulted')
                        );
                        NotificationService::notifyUser($loan->user, 'loan', 'Loan Defaulted', 'Your loan has been marked as defaulted due to multiple overdue payments. Your collateral has been liquidated.', url('dashboard/loans/' . $loan->id));
                    } catch (\Exception $e) {
                        Log::error('Loan defaulted mail failed: ' . $e->getMessage());
                    }
                }
            }
        }

        return response()->json([
            'status' => 'Loan check completed',
            'results' => $results,
        ]);
    }
}
