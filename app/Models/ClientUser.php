<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUser extends Model
{
    use SoftDeletes;
    protected $dates =['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'user_id', 'id');
    }
}
