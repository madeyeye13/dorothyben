<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    protected $fillable = [
        'title', 'url', 'description', 'currency_tag', 'active', 'sort_order',
    ];

    protected $casts = ['active' => 'boolean'];
}