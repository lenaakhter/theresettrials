<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'username',
        'username_changed_at',
        'email',
        'password',
        'is_admin',
        'email_notifications_opt_in',
        'banned_until',
        'ban_duration_hours',
        'ban_started_at',
        'ban_reason',
        'profile_photo',
        'avatar_focus_x',
        'avatar_focus_y',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'is_admin' => 'boolean',
            'email_notifications_opt_in' => 'boolean',
            'banned_until' => 'datetime',
            'ban_started_at' => 'datetime',
            'username_changed_at' => 'datetime',
            'avatar_focus_x' => 'float',
            'avatar_focus_y' => 'float',
        ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function getCommentNameAttribute(): string
    {
        return $this->display_name ?: $this->name;
    }

    public function hasRequiredProfileInfo(): bool
    {
        return filled($this->display_name) && filled($this->username);
    }

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
