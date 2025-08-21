<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\MiTiendaApiController;
use App\Http\Middleware\VerifyCsrfToken;
Route::middleware(['web','auth'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->prefix('user/api/mi-tienda')
    ->group(function(){
        Route::get('/ping',[MiTiendaApiController::class,'ping'])->name('user.mitienda.ping');
        Route::get('/state',[MiTiendaApiController::class,'state'])->name('user.mitienda.state');
        Route::get('/inventory',[MiTiendaApiController::class,'inventory'])->name('user.mitienda.inventory');
        Route::post('/state',[MiTiendaApiController::class,'save'])->name('user.mitienda.save');
    });