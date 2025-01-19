<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrower extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "borrowers";

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'national_id',
        'employment_status',
        'employment_name',
        'income',
        'date_of_birth',
        'gender',
        'sponsor_name',
        'branch_id',
        'loanee_id',
        'status',
        'email_verified_at',
        'password',
        'avatar'
    ];
}
