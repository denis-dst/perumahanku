<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'avatar', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        if (str_starts_with($this->avatar, 'uploads/') || str_starts_with($this->avatar, '/uploads/')) {
            return asset(ltrim($this->avatar, '/'));
        }

        if (str_starts_with($this->avatar, 'images/') || str_starts_with($this->avatar, '/images/')) {
            return asset(ltrim($this->avatar, '/'));
        }

        return asset('storage/'.$this->avatar);
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name ?? 'Admin'));
        $initials = '';
        foreach ($words as $w) {
            if (! empty($w)) {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }

        return $initials ?: 'AD';
    }
}
