<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\Card\CardStoreRequest;
use App\Http\Requests\Board\Card\CardUpdateRequest;
use App\Http\Resources\CardResource;
use App\Http\Services\CardService;
use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CardController extends Controller
{
    use AuthorizesRequests;
    public function __construct(public CardService $cardService) {}

    public function store(BoardList $boardList, CardStoreRequest $request)
    {
        $this->authorize('create', [Card::class, $boardList]);
        $result = $this->cardService->createCard($boardList, $request->validated());
        return response()->json([
            'message' => 'Card created successfully',
            'card' => new CardResource($result) 
        ]);
    }

    public function update(BoardList $boardList, Card $card, CardUpdateRequest $request)
    {
        $this->authorize('update', $card);
        $result = $this->cardService->updateCard($card, $request->validated());
        return response()->json([
            'message' => 'Card updated successfully',
            'card' => new CardResource($result->load(['createdBy']))
        ]);
    }

    public function delete(BoardList $boardList, Card $card)
    {
        $this->authorize('delete', $card);
        $this->cardService->deleteCard($card);
        return response()->json([
            'message' => 'Card deleted successfully'
        ]);
    }
}
