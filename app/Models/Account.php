<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    protected $dates =['deleted_at'];

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'loan_status'
    ];

    public function user()
    {
        return $this->belongsTo(ClientUser::class, 'user_id', 'id');
    }

    public function loan()
    {
        return $this->hasOne(Loan::class, 'account_id','id');
    }
}
