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

Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing.index');
Route::get('/about', [App\Http\Controllers\LandingPageController::class, 'about'])->name('landing.about');
Route::get('/rooms', [App\Http\Controllers\LandingPageController::class, 'rooms'])->name('landing.rooms');
Route::get('/cottages', [App\Http\Controllers\LandingPageController::class, 'cottages'])->name('landing.cottages');
Route::get('/contact', [App\Http\Controllers\LandingPageController::class, 'contact'])->name('landing.contact');
Route::post('/reservation-store', [App\Http\Controllers\LandingPageController::class, 'reservation_store'])->name('landing.reservation-store');

// Auth::routes();
Auth::routes(['register' => false, 'reset' => false]);

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/transaction_datatables', [App\Http\Controllers\DashboardController::class, 'transaction_datatables'])->name('dashboard.transaction_datatables');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

Route::get('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'index'])->name('changepassword.index');
Route::put('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'update'])->name('changepassword.update');

Route::resource('user', App\Http\Controllers\UserController::class)->except(['index', 'show']);
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
Route::get('/users-datatables', [App\Http\Controllers\UserController::class, 'datatables'])->name('user.datatables');

Route::resource('room', App\Http\Controllers\RoomController::class)->except(['index', 'show']);
Route::get('/all-rooms', [App\Http\Controllers\RoomController::class, 'index'])->name('room.index');
Route::get('/rooms-datatables', [App\Http\Controllers\RoomController::class, 'datatables'])->name('room.datatables');
Route::put('/room-image', [App\Http\Controllers\RoomController::class, 'image_remove'])->name('room.image.remove');

Route::resource('cottage', App\Http\Controllers\CottageController::class)->except(['index', 'show']);
Route::get('/all-cottages', [App\Http\Controllers\CottageController::class, 'index'])->name('cottage.index');
Route::get('/cottages-datatables', [App\Http\Controllers\CottageController::class, 'datatables'])->name('cottage.datatables');
Route::put('/cottage-image', [App\Http\Controllers\CottageController::class, 'image_remove'])->name('cottage.image.remove');

Route::resource('client', App\Http\Controllers\ClientController::class)->except(['index']);
Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->name('client.index');
Route::get('/clients-datatables', [App\Http\Controllers\ClientController::class, 'datatables'])->name('client.datatables');
Route::get('/get_clients', [App\Http\Controllers\ClientController::class, 'get_clients'])->name('client.get_clients');

Route::resource('transaction', App\Http\Controllers\TransactionController::class)->except(['index']);
Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
Route::get('/transactions-datatables', [App\Http\Controllers\TransactionController::class, 'datatables'])->name('transaction.datatables');
Route::get('/transaction/invoice/{id}', [App\Http\Controllers\TransactionController::class, 'invoice'])->name('transaction.invoice');
Route::post('/transaction/invoice/{id}/pay', [App\Http\Controllers\TransactionController::class, 'pay'])->name('transaction.pay');
Route::post('/transaction/invoice/{id}/unpaid', [App\Http\Controllers\TransactionController::class, 'unpaid'])->name('transaction.unpaid');

Route::resource('reservation', App\Http\Controllers\ReservationController::class)->except(['index', 'show', 'update', 'store']);
Route::get('/reservations', [App\Http\Controllers\ReservationController::class, 'index'])->name('reservation.index');
Route::get('/reservations-datatables', [App\Http\Controllers\ReservationController::class, 'datatables'])->name('reservation.datatables');
Route::put('/reservation-approve', [App\Http\Controllers\ReservationController::class, 'approve'])->name('reservation.approve');

Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
Route::get('/reports-datatables', [App\Http\Controllers\ReportController::class, 'datatables'])->name('report.datatables');

Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');

Route::resource('/settings/entrancefee', App\Http\Controllers\EntrancefeeController::class)->only(['edit', 'update']);
Route::resource('/settings/breakfast', App\Http\Controllers\BreakfastController::class)->except(['index', 'show']);
Route::resource('resort', App\Http\Controllers\ResortController::class)->only(['update']);