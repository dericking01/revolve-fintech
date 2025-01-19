<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "loan_installments";


    protected $fillable = [
        'loan_id',
        'installed_amount',
        'overdue_date',
        'paid_date',
        'status',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
