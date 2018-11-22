<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;
    protected $dates =['deleted_at'];

    protected $fillable = [
        'account_id',
        'type',
        'repayments_frequency',
        'status',
        'duration',
        'interest_rate',
        'amount',
        'paid_amount',
        'balance_amount',
        'number_of_instalment',
        'instalment_amount',
        'arrangement_fee',
        'penalty_fee'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class, 'loan_id','id');
    }
}
