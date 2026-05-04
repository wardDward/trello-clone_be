<?php

namespace App\Http\Services;

use App\Http\Enums\BoardVisibility;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;

class BoardService
{
    public function getBoards($user, int $page = 1, int $pageSize = 10): array
    {
        $query = $user->boards()->with('owner')->orderBy('created_at', 'desc');
        $total = $query->count();

        $boards = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        return [
            'data' => $boards,
            'total' => $total,
        ];
    }

    public function createBoard(array $data)
    {
        $board = Board::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'owner_id' => Auth::user()->id,
            'visibility' => BoardVisibility::from($data['visibility']),
            'background' => $data['background'] ?? null,
        ]);

        return $board;
    }

    public function getBoardByUuid(Board $board)
    {
        return $board;
    }

    public function updateBoard(Board $board, array $data)
    {
        $board->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'visibility' => BoardVisibility::from($data['visibility']),
            'background' => $data['background'] ?? null,
        ]);

        return [
            'message' => 'Board updated successfully',
            'board' => $board,
        ];
    }

    public function deleteBoard(Board $board)
    {
        $board->delete();
        return [
            'message' => 'Board deleted successfully',
        ];
    }
}
