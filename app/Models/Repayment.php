<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use SoftDeletes;
    protected $dates =['deleted_at'];

    protected $fillable = [
        'loan_id',
        'amount',
        'status',
        'penalty_fee',
        'payment_date'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id','id');
    }
}
