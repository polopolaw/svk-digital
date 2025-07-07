<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\PlaceController;
use App\Http\Controllers\Api\V1\ShowController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('shows')->group(function (): void {
        Route::get('/', [ShowController::class, 'index'])
            ->name('shows.index');

        Route::get('/{showId}/events', [EventController::class, 'index'])
            ->name('shows.events.index')
            ->where('showId', '[0-9]+');
    });

    Route::prefix('events')->group(function (): void {
        Route::get('/{eventId}/places', [PlaceController::class, 'index'])
            ->name('events.places.index')
            ->where('eventId', '[0-9]+');

        Route::post('/{eventId}/reserve', [PlaceController::class, 'reserve'])
            ->name('events.reserve.store')
            ->where('eventId', '[0-9]+');
    });
});

Route::get('/health', fn () => response()->json(['status' => 'ok']));
