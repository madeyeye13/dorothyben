<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'currency', 'bank_name', 'account_name', 'account_number',
        'sort_code', 'routing_number', 'active', 'sort_order',
        'payment_link', 'payment_link_label',
    ];

    protected $casts = ['active' => 'boolean'];
}