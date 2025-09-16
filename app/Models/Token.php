<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    // Token masih valid jika belum dipakai dan belum kadaluarsa
    public function isValid(): bool
    {
        // Pastikan expires_at adalah instance Carbon dan belum dipakai
        return !$this->used_at
            && $this->expires_at instanceof \Illuminate\Support\Carbon
            && $this->expires_at->isFuture() || $this->expires_at === null;
    }

    // Token sudah expired jika expires_at null atau sudah lewat
    public function isExpired(): bool
    {
        return !($this->expires_at instanceof \Illuminate\Support\Carbon)
            || $this->expires_at->isPast();
    }

    // Tandai token sudah digunakan
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
