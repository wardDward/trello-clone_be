<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['board_id', 'title', 'order_index'])]
#[Hidden(['id'])]
class BoardList extends Model
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
     * Get the board that owns the list.
     *
     * @return BelongsTo
     */
    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
}
