<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\BoardStoreRequest;
use App\Http\Requests\Board\BoardUpdateRequest;
use App\Http\Resources\BoardResource;
use App\Http\Services\BoardService;
use App\Models\Board;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    use AuthorizesRequests;
    public function __construct(public BoardService $service) {}

    public function index(Request $request)
    {
        $result = $this->service->getBoards($request->user(), $request->query('page', 1));

        return response()->json([
            'boards' => BoardResource::collection($result['data']->load(['owner'])),
            'total' => $result['total'],
        ]);
    }

    public function store(BoardStoreRequest $request)
    {
        $board = $this->service->createBoard($request->validated());

        return new BoardResource($board->load(['owner']));
    }

    public function show(Board $board)
    {
        $board = $this->service->getBoardByUuid($board);

        return new BoardResource($board->load(['owner']));
    }

    public function update(Board $board, BoardUpdateRequest $request)
    {   
        $this->authorize('update', $board);
        $board = $this->service->updateBoard($board, $request->validated());
        return response()->json([
            'message' => $board['message'],
            'board' => new BoardResource($board['board']->load(['owner'])),
        ]);
    }

    public function delete(Board $board){
        $this->authorize('delete', $board);
        return $this->service->deleteBoard($board);
    }
}
