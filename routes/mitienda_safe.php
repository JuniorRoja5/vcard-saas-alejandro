<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CardController;

// Middlewares referenced so we can strip them explicitly if present
use App\Http\Middleware\ScriptSanitizer;
use App\Http\Middleware\TwoFactorAuthentication;
use App\Http\Middleware\FrameDestroyerMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\Installer;
use App\Http\Middleware\SetLocale;

Route::middleware(['web','auth'])
    ->withoutMiddleware([ScriptSanitizer::class, TwoFactorAuthentication::class, FrameDestroyerMiddleware::class, UserMiddleware::class, Installer::class, SetLocale::class])
    ->group(function () {
        Route::get('/safe/health', function(){ return response('OK', 200)->header('Cache-Control','no-store'); })->name('safe.health');
        Route::get('/safe/cards', [CardController::class, 'safeIndex'])->name('safe.cards');
        Route::get('/safe/cards/{card}', [CardController::class, 'builderBare'])->name('safe.cards.builder');
    });
