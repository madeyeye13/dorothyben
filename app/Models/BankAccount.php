<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'currency', 'bank_name', 'account_name', 'account_number',
        'sort_code', 'routing_number', 'active', 'sort_order',
    ];

    protected $casts = ['active' => 'boolean'];
}
