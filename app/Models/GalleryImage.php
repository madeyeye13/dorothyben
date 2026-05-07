<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['filename', 'path', 'caption', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
