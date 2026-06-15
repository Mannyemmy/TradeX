<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id', 'installment_number', 'due_date',
        'principal_amount', 'interest_amount', 'total_amount',
        'status', 'paid_amount', 'paid_at', 'late_fee',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
    ];

    // ── Relationships ──

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'loan_repayment_schedule_id');
    }

    // ── Scopes ──

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeDue($query)
    {
        return $query->where('status', 'due');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['upcoming', 'due', 'overdue']);
    }

    /**
     * Get the total amount due including any late fee.
     */
    public function getTotalDueAttribute(): float
    {
        return round($this->total_amount + $this->late_fee - $this->paid_amount, 2);
    }
}
