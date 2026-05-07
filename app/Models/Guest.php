<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'email', 'phone', 'attending',
        'decline_reason', 'relationship', 'qr_token', 'qr_used', 'qr_used_at',
    ];

    protected $casts = [
        'relationship' => 'array',
        'qr_used'      => 'boolean',
        'qr_used_at'   => 'datetime',
    ];

    public function companions()
    {
        return $this->hasMany(GuestCompanion::class);
    }

    public static function generateQrToken(): string
    {
        do {
            $token = Str::random(32);
        } while (static::where('qr_token', $token)->exists());

        return $token;
    }

    public function getRelationshipLabelAttribute(): string
    {
        $map = [
            'groom_friend' => "Groom's Friend",
            'bride_friend' => "Bride's Friend",
            'family'       => 'Family',
        ];
        $labels = collect($this->relationship ?? [])->map(fn($r) => $map[$r] ?? $r);
        return $labels->join(', ');
    }

    public function isAttending(): bool
    {
        return $this->attending === 'yes';
    }
}
