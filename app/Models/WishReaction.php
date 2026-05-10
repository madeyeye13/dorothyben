<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishReaction extends Model
{
    protected $fillable = ['wish_id', 'reaction_type', 'session_id'];

    public function wish()
    {
        return $this->belongsTo(Wish::class);
    }
}