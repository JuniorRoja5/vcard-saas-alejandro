<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CardController;
Route::middleware(['web','auth'])->prefix('user')->as('user.')->group(function(){
    Route::get('/cards/{card}/builder',[CardController::class,'builder'])->name('cards.builder');
});