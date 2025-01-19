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

    public function creator()
    {
        return $this->morphTo('created_by');
    }

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

}
