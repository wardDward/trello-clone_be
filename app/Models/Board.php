<?php

namespace App\Models;

use App\Enums\BoardVisibility;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'owner_id', 'visibility', 'background'])]
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

    public $casts = [
        'visibility' => BoardVisibility::class,
    ];

    /**
     * Get the owner of the board.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
