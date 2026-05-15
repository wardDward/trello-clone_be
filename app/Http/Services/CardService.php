<?php

namespace App\Http\Services;

use App\Http\Enums\CardPriority;
use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardService{
    public function createCard(BoardList $boardList, array $data){
        $card = $boardList->cards()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'order_index' => $boardList->cards()->max('order_index') ? $boardList->cards()->max('order_index') + 1 : 1,
            'due_date' => $data['due_date'] ?? null,
            'priority' => CardPriority::from($data['priority'] ?? CardPriority::LOW->value),
            'created_by' => Auth::id(),   
        ]);

        return $card;
    }

    public function updateCard(Card $card, array $data){
        $card->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'priority' => CardPriority::from($data['priority'] ?? CardPriority::LOW->value),
        ]);

        return $card;
    }

    public function deleteCard(Card $card){
        $card->delete();
    }
}