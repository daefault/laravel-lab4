<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->username)) {
                $user->username = $user->generateUsername();
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('name') && empty($user->username)) {
                $user->username = $user->generateUsername();
            }
        });
    }
    public function generateUsername()
    {
        $baseUsername = Str::slug($this->name, '-');
        $username = $baseUsername;
        $counter = 1;

        // Проверяем уникальность
        while (
            self::where('username', $username)
                ->where('id', '!=', $this->id)
                ->exists()
        ) {
            $username = $baseUsername . '-' . $counter;
            $counter++;
        }

        return $username;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_admin',
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
        'is_admin' => 'boolean',
    ];
    public function characters()
    {
        return $this->hasMany(\App\Models\Character::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }
    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }
    public function allFriends()
    {
        return $this->friends->merge($this->friendOf);
    }
    public function isFriendWith(User $user)
    {
        return $this->friends()->where('friend_id', $user->id)->exists() ||
            $this->friendOf()->where('user_id', $user->id)->exists();
    }
    public function hasSentFriendRequestTo(User $user)
    {
        return $this->sentFriendRequests()
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }
    public function hasReceivedFriendRequestFrom(User $user)
    {
        return $this->receivedFriendRequests()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }
    

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
    public function getRouteKeyName()
    {
        return 'username';
    }
}
