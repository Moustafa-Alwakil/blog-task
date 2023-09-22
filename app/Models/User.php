<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static string $storage = 'users-avatars';


    // Start Relations

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    // End Relations


    // Start Helpers

    public function getAvatar(): string
    {
        return $this->avatar ? url('storage/' . self::$storage . '/' . $this->avatar) : asset('assets/images/user.png');
    }

    public function deleteAvatar(): void
    {
        if ($this->avatar) {
            if (Storage::exists('public/' . self::$storage . '/' . $this->avataravatar))
                Storage::delete('public/' . self::$storage . '/' . $this->avatar);
        }
    }

    // End Helpers
}
