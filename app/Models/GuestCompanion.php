<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCompanion extends Model
{
    protected $fillable = ['guest_id', 'name', 'relation'];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
