<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\BoardStoreRequest;
use App\Http\Requests\Board\BoardUpdateRequest;
use App\Http\Requests\Board\MemberRequest;
use App\Http\Resources\BoardResource;
use App\Http\Resources\UserResource;
use App\Http\Services\BoardService;
use App\Models\Board;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BoardController extends Controller
{
    use AuthorizesRequests;

    public function __construct(public BoardService $service) {}

    public function index(Request $request)
    {
        $result = $this->service->getBoards(Auth::user(), $request->query('page', 1));

        return response()->json([
            'boards' => BoardResource::collection($result['data']->load(['owner', 'members', 'lists'])),
            'total' => $result['total'],
        ]);
    }

    public function store(BoardStoreRequest $request)
    {
        $this->authorize('create', Board::class);
        $board = $this->service->createBoard($request->validated());

        return response()->json([
            'message' => 'Board created successfully',
            'board' => new BoardResource($board->load(['owner', 'members'])),
        ], Response::HTTP_CREATED);
    }

    public function show(Board $board)
    {
        $board = $this->service->getBoardByUuid($board);

        return new BoardResource($board->load(['owner', 'members']));
    }

    public function update(Board $board, BoardUpdateRequest $request)
    {
        $this->authorize('update', $board);
        $board = $this->service->updateBoard($board, $request->validated());

        return response()->json([
            'message' => 'Board updated successfully',
            'board' => new BoardResource($board['board']->load(['owner', 'members'])),
        ]);
    }

    public function delete(Board $board)
    {
        $this->authorize('delete', $board);
        $this->service->deleteBoard($board);
        return response()->json(['message' => 'Board deleted successfully']);
    }

    public function addMembers(Board $board, MemberRequest $request)
    {
        $this->authorize('update', $board);
        $members = $this->service->addMembers($board, $request->validated());

        return UserResource::collection($members);
    }

    public function removeMembers(Board $board, MemberRequest $request)
    {
        $this->authorize('update', $board);
        $removed = $this->service->removeMembers($board, $request->validated());

        return UserResource::collection($removed);
    }
}
