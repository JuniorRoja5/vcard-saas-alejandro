<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CardController;

// Import middlewares so we can selectively disable them for builder routes
use App\Http\Middleware\ScriptSanitizer;
use App\Http\Middleware\TwoFactorAuthentication;
use App\Http\Middleware\FrameDestroyerMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\Installer;
use App\Http\Middleware\SetLocale;

Route::middleware(['web','auth'])->prefix('user')->as('user.')->group(function(){
    // List route name expected by dashboard
    Route::get('/cards',[CardController::class,'index'])->name('cards');

    Route::post('/cards',[CardController::class,'store'])->name('cards.store');
    Route::get('/cards/create',[CardController::class,'create'])->name('cards.create');

    // Builder routes: strip middlewares that commonly cause redirect loops inside dashboards
    Route::get('/cards/{card}/builder-bare',[CardController::class,'builderBare'])
        ->name('cards.builder.bare')
        ->withoutMiddleware([ScriptSanitizer::class, TwoFactorAuthentication::class, FrameDestroyerMiddleware::class, UserMiddleware::class, Installer::class, SetLocale::class]);

    Route::get('/cards/{card}/builder',[CardController::class,'builder'])
        ->name('cards.builder')
        ->withoutMiddleware([ScriptSanitizer::class, TwoFactorAuthentication::class, FrameDestroyerMiddleware::class, UserMiddleware::class, Installer::class, SetLocale::class]);

    Route::post('/cards/{card}',[CardController::class,'update'])->name('cards.update');
    Route::delete('/cards/{card}',[CardController::class,'destroy'])->name('cards.destroy');
});
