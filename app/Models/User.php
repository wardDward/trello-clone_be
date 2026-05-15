<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['first_name', 'middle_name', 'last_name', 'username', 'email', 'password', 'avatar'])]
#[Hidden(['id','password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasApiTokens ,HasUuids, Notifiable;

    /*
        * Get the unique identifiers for the model.
        *
        * @return array<int, string>
        */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

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

    /**
     * Get the boards owned by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boards(){
        return $this->hasMany(Board::class, 'owner_id');
    }

        /**
        * Get the boards the user is a member of.
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
        */
    public function boardMembers(){
        return $this->belongsToMany(Board::class, 'board_members', 'user_id', 'board_id')->withPivot('role')->withTimestamps();
    }

    public function starBoard(){
        return $this->belongsToMany(Board::class, 'starred_boards', 'user_id', 'board_id')->withTimestamps();
    }


}
