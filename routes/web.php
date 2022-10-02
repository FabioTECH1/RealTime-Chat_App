<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['guest:web'])->group(function () {

    Route::get('/', [LoginController::class, 'index'])->name('index');

    Route::post('/login', [LoginController::class, 'login'])->name('login');

    Route::get('/register', [RegisterController::class, 'index'])->name('register');

    Route::post('/register-user', [RegisterController::class, 'store'])->name('register-user');
});


Route::middleware(['auth:web'])->group(function () {

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/convos', [ConversationController::class, 'index'])->name('convos');

    Route::post('/get-convo', [ConversationController::class, 'get_convo'])->name('get_convo');

    Route::post('/search', [ConversationController::class, 'search'])->name('search');


    Route::get('/chats/{id}', [ChatController::class, 'index'])->name('chats');

    Route::post('/{id}/message', [MessageController::class, 'message'])->name('message');

    Route::post('/{id}/get-message', [MessageController::class, 'getmessage'])->name('getmessage');
});
