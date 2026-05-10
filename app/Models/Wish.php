<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    protected $fillable = [
        'name', 'message', 'approved',
        'admin_reply', 'replied_at',
        'heart_count', 'congrats_count',
    ];

    protected $casts = [
        'approved'   => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function reactions()
    {
        return $this->hasMany(WishReaction::class);
    }
}