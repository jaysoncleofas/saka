<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
Auth::routes(['register' => false, 'reset' => false]);

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

Route::get('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'index'])->name('changepassword.index');
Route::put('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'update'])->name('changepassword.update');

Route::resource('user', App\Http\Controllers\UserController::class)->except(['index', 'show']);

Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
Route::get('/users-datatables', [App\Http\Controllers\UserController::class, 'datatables'])->name('user.datatables');
