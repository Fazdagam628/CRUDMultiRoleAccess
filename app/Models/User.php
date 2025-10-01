<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_admin',
        'token',
        'expires_at',
        'used_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];
    protected $casts = [
        'is_admin'   => 'boolean',
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
    // apakah session (login) user masih berlaku
    public function sessionValid(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        if ($this->used_at) {
            return false;
        }

        if (!($this->expires_at instanceof \Illuminate\Support\Carbon)) {
            return false;
        }

        return $this->expires_at->isFuture();
    }

    // apakah session sudah expired
    public function sessionExpired(): bool
    {
        if ($this->is_admin) {
            return false;
        }
        if (!($this->expires_at instanceof \Illuminate\Support\Carbon)) {
            return true;
        }
        return $this->expires_at->isPast();
    }

    // tandai session/user sudah dipakai (set used_at)
    public function markSessionUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    // mulai session untuk user (set expires_at)
    public function startSession(int $minutes = 5): void
    {
        $this->update([
            'expires_at' => now()->addMinutes($minutes),
            'used_at' => null,
        ]);
    }

    public function tokens()
    {
        return $this->hasMany(\App\Models\Token::class);
    }
}
