<?php

namespace App\Models;

use App\Http\Enums\Board\BoardBackground;
use App\Http\Enums\Board\BoardVisibility;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'description', 'owner_id', 'visibility', 'background', 'background_type'])]
#[Hidden(['id'])]
class Board extends Model
{
    use HasUuids;

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
    public $casts = [
        'visibility' => BoardVisibility::class,
        'background_type' => BoardBackground::class,
    ];


    public function isAdmin(User $user){
        return $this->members()->where('user_id', $user->id)->wherePivot('role', 'ADMIN')->exists();
    }

    /**
     * Get the owner of the board.
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    

    /**
     * Summary of members
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User, Board, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function members(){
        return $this->belongsToMany(User::class, 'board_members', 'board_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    // board list relationship
    public function lists(){
        return $this->hasMany(BoardList::class);
    }

}
