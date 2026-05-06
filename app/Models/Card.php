<?php

namespace App\Models;

use App\Http\Enums\CardPriority;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['board_list_id', 'title', 'description', 'order_index', 'due_date', 'priority', 'created_by'])]
#[Hidden(['id', 'board_list_id'])]
class Card extends Model
{
    use HasUuids;

    /*
    * Get the unique identifiers for the model.
    *
    * @return array<int, string>
    */
    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'priority' => CardPriority::class,
    ];
    
    /**
     * Summary of boardList
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BoardList, Card>
     */
    public function boardList()
    {
        return $this->belongsTo(BoardList::class);
    }

    /**
     * Summary of creator
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Card>
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
