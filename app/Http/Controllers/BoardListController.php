<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\BoardListStoreRequest;
use App\Http\Requests\Board\BoardListUpdateRequest;
use App\Http\Resources\BoardListResource;
use App\Http\Resources\BoardResource;
use App\Http\Services\BoardService;
use App\Models\Board;
use App\Models\BoardList;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BoardListController extends Controller
{
    use AuthorizesRequests;

    public function __construct(public BoardService $boardService){}
    public function store(Board $board, BoardListStoreRequest $request){
        $this->authorize('create', [BoardList::class, $board]);
        $result = $this->boardService->createBoardList($board, $request->validated());
        return response()->json([
            'message' => 'List created successfully',
            'list' => new BoardListResource($result)
        ]);
    }
    
    public function update(Board $board, BoardList $list, BoardListUpdateRequest $request){
        $this->authorize('update', $list);
        $result = $this->boardService->updateBoardList($list, $request->validated());
        return response()->json([
            'message' => 'List updated successfully',
            'list' => new BoardListResource($result)
        ]);
    }

    public function delete(Board $board, BoardList $list){
        $this->authorize('delete', $list);
        $this->boardService->deleteBoardList($list);
        return response()->json([
            'message' => 'List deleted successfully'
        ]);
    }
}
