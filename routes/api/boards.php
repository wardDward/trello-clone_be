<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardListController;
use Illuminate\Support\Facades\Route;

Route::prefix('board')->middleware(['auth:sanctum'])->group(function(){
    Route::get('', [BoardController::class, 'index']);
    Route::post('', [BoardController::class, 'store']);
    Route::get('{board:uuid}', [BoardController::class, 'show']); 
    Route::match(['put', 'patch'], '{board:uuid}', [BoardController::class, 'update']); 
    Route::delete('{board:uuid}', [BoardController::class, 'delete']);
    Route::match(['put', 'patch'],'{board:uuid}/add_members', [BoardController::class, 'addMembers']);
    Route::match(['put', 'patch'],'{board:uuid}/remove_members', [BoardController::class, 'removeMembers']);
});

Route::prefix('board/{board:uuid}/lists')->middleware(['auth:sanctum'])->group(function(){
    Route::post('', [BoardListController::class, 'store']);
    Route::match(['put', 'patch'], '{list:uuid}', [BoardListController::class, 'update']);
    Route::delete('{list:uuid}', [BoardListController::class, 'delete']);
});