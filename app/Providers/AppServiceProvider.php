<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        // belongs to board 
        Gate::define('belongs-to-board', function ($user, $board) {
            return $board->members()->where('user_id', $user->id)->exists() || $board->owner_id === $user->id;
        });

        // Board Admin
        Gate::define('board-admin', function($user, $board){
            return $board->isAdmin($user);
        });
    }
}
