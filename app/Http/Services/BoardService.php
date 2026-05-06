<?php

namespace App\Http\Services;

use App\Http\Enums\Board\BoardMember;
use App\Http\Enums\Board\BoardVisibility;
use App\Models\Board;
use App\Models\BoardList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoardService
{
    public function getBoards(User $user, int $page = 1, int $pageSize = 10): array
    {
        $query = $user->boards()->with(['owner', 'lists'])->orderBy('created_at', 'desc');
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

        $board = DB::transaction(function () use ($data) {
            $board = Board::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'owner_id' => Auth::user()->id,
                'visibility' => BoardVisibility::from($data['visibility']),
                'background' => $data['background'] ?? null,
            ]);

            if (isset($data['members']) && is_array($data['members'])) {
                $uuids = collect($data['members'])->pluck('uuid');
                $users = User::whereIn('uuid', $uuids)->get()->keyBy('uuid');

                $members = collect($data['members'])->mapWithKeys(function ($member) use ($users) {
                    $user = $users->get($member['uuid']);
                    return [$user->id => ['role' => BoardMember::from($member['role'])]];
                })->toArray();
                $board->members()->attach($members);
            }
            
            $board->members()->attach(Auth::user()->id, ['role' => BoardMember::ADMIN->value]);
            return $board;
        });

        return $board;
    }

    public function getBoardByUuid(Board $board)
    {
        return $board->load(['owner', 'members', 'lists']);
    }

    public function updateBoard(Board $board, array $data)
    {   

        $board = DB::transaction(function() use ($board, $data){
           
        $board->update([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'visibility' => BoardVisibility::from($data['visibility']),
                    'background' => $data['background'] ?? null,
                ]);

                if (isset($data['members']) && is_array($data['members'])) {
                    $uuids = collect($data['members'])->pluck('uuid');
                    $users = User::whereIn('uuid', $uuids)->get()->keyBy('uuid');

                    $members = collect($data['members'])->mapWithKeys(function($member) use ($users){
                        $user = $users->get($member['uuid']);
                        return [$user->id => ['role' => BoardMember::from($member['role'])]];
                    })->toArray();

                    $board->members()->syncWithoutDetaching($members);
                }

                return $board;
            });

        return [
            'board' => $board,
        ];
    }

    public function deleteBoard(Board $board)
    {
        DB::transaction(function () use ($board){
            $board->members()->detach();
            $board->delete();
        });
    }

    public function addMembers(Board $board, array $data){
        $uuids = collect($data['members'])->pluck('uuid');
        $users = User::whereIn('uuid', $uuids)->get()->keyBy('uuid');

        $members = collect($data['members'])->mapWithKeys(function($member) use ($users){
            $user = $users->get($member['uuid']);
            return [$user->id => ['role' => BoardMember::from($member['role'])]];
        })->toArray();

        $board->members()->syncWithoutDetaching($members);

        return $board->members()->wherePivotIn('user_id', array_keys($members))->get();
    }


    public function removeMembers(Board $board, array $data){
        $uuids = collect($data['members'])->pluck('uuid');
        $users = User::whereIn('uuid', $uuids)->get()->keyBy('uuid');

        $userIds = $users->pluck('id')
            ->reject(fn($id) => $id === $board->owner_id)
            ->toArray();

        $removed = $board->members()->wherePivotIn('user_id', $userIds)->get();
        $board->members()->detach($userIds);

        return $removed;
    }   


    // board list
    public function createBoardList(Board $board, array $data){
        $boardList = $board->lists()->create([
            'title' => $data['title'],
            'order_index' => $board->lists()->max('order_index') ? $board->lists()->max('order_index') + 1 : 1,
        ]);

        return $boardList;
    }

    public function updateBoardList(BoardList $list, array $data){
        $list->update([
            'title' => $data['title'] ?? $list->title,
        ]);

        return $list;
    }

    public function deleteBoardList(BoardList $list){
        return $list->delete();
    }
    
}
