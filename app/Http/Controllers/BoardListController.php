<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\BoardListStoreRequest;
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
    public function index(){}
    public function store(Board $board, BoardListStoreRequest $request){
        $this->authorize('create', [BoardList::class, $board]);
        $result = $this->boardService->createBoardList($board, $request->validated());
        return new BoardListResource($result);
    }

 
}
