<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\DMController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CharacterSheetController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::middleware(['auth'])->group(function () {


        Route::prefix('player')->name('player.')->group(function () {
            Route::get('/dashboard', [PlayerController::class, 'dashboard'])->name('dashboard');
        });

        Route::prefix('character-sheets')->name('player.characterSheets.')->group(function () {
            Route::get('/create', [CharacterSheetController::class, 'create'])->name('create');
            Route::post('/store', [CharacterSheetController::class, 'store'])->name('store');
            Route::get('/character-sheets/{id}', [CharacterSheetController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CharacterSheetController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CharacterSheetController::class, 'update'])->name('update');

            Route::delete('/character-sheets/{characterSheet}', [CharacterSheetController::class, 'destroy'])->name('destroy');

        });

    // rutas para middleware de dm
    Route::prefix('dm')->name('dm.')->middleware('dm')->group(function () {
        Route::get('/dashboard', [DMController::class, 'dashboard'])->name('dashboard');

        Route::get('/sessions/create', [DMController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [DMController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/{session}', [DMController::class, 'show'])->name('sessions.show');
        Route::get('/sessions/{session}/edit', [DMController::class, 'edit'])->name('sessions.edit');
        Route::put('/sessions/{session}', [DMController::class, 'update'])->name('sessions.update');
        Route::delete('/sessions/{session}', [DMController::class, 'destroy'])->name('sessions.destroy');
    });


    //rutas para middleware de admin
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('destroyUser');
        Route::delete('/sessions/{session}', [AdminController::class, 'destroySession'])->name('destroySession');
        Route::delete('/character-sheets/{characterSheet}', [AdminController::class, 'destroyCharacterSheet'])->name('destroyCharacterSheet');
    });



    Route::get('/players', [PlayerController::class, 'getPlayers'])->name('players.get');
    Route::get('/dm/get-players', [DMController::class, 'getPlayers'])->name('dm.getPlayers');
    Route::get('/dm/players/{playerId}/character-sheets', [DMController::class, 'getCharacterSheets'])->name('dm.getCharacterSheets');



});
