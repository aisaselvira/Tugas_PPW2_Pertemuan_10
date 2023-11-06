<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterCustomController;
use App\Http\Controllers\UserCustomController;

// use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::controller(LoginRegisterCustomController::class)->group(function () {
    Route::get('/register', 'register2')->name('register2');
    Route::post('/store', 'store2')->name('store2');
    Route::get('/login', 'login2')->name('login2');
    Route::post('/authenticate', 'authenticate2')->name('authenticate2');
    Route::get('/dashboard', 'dashboard2')->name('dashboard2');
    Route::post('/logout', 'logout2')->name('logout2');
});

Route::get('/user2', [UserCustomController::class, 'index2'])->name('user2');

Route::delete('/user2/{user}', [UserCustomController::class, 'destroy2'])->name('destroy2');
Route::get('/user2/{user}/edit_resize', [UserCustomController::class, 'edit_resize'])->name('edit_resize');
Route::get('/user2/{user}/edit2', [UserCustomController::class, 'edit2'])->name('edit2');
Route::patch('/user2/{user}', [UserCustomController::class, 'update2'])->name('update2');
Route::get('/user2/{user}/resize2', [UserCustomController::class, 'resizeForm'])->name('resizeForm');
Route::post('/user2/{user}/resize2', [UserCustomController::class, 'resizeImage'])->name('resizeImage');
