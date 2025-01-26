<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    const TERMS = [1, 2, 3]; // Valid terms in months

    protected $fillable = [
        'borrower_id',
        'created_by_id',
        'created_by_type',
        'application_fee',
        'loan_amount',
        'interest_rate',
        'term',
        'start_date',
        'due_date',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
    ];


    public function creator()
    {
        return $this->morphTo('created_by');
    }

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function calculatePenalty()
    {
        $overdueDays = now()->diffInDays($this->due_date, false); // Negative if overdue
        if ($overdueDays > 0) {
            $penaltyRate = $this->daily_penalty_rate / 100; // Convert percentage to decimal
            return $this->loan_amount * $penaltyRate * $overdueDays;
        }

        return 0.00;
    }

    public function updatePenalty()
    {
        $penalty = $this->calculatePenalty();
        $this->penalty_amount = $penalty;

        // Recalculate total amount to be paid
        $this->amount_to_be_paid = $this->loan_amount + $penalty - $this->paid_amount;

        $this->save();
    }

    public function addInstallment($installedAmount)
    {
        // Increment paid amount
        $this->paid_amount += $installedAmount;

        // Recalculate total amount to be paid
        $this->amount_to_be_paid = $this->loan_amount + $this->penalty_amount - $this->paid_amount;

        $this->save();
    }

}
