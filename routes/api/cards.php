<?php

use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

Route::prefix('board_list/{boardList:uuid}/cards')->middleware(['auth:sanctum'])->group(function(){
    Route::post('', [CardController::class, 'store']);
    Route::match(['put', 'patch'], '{card:uuid}', [CardController::class, 'update']);
    Route::delete('{card:uuid}', [CardController::class, 'delete']);
});