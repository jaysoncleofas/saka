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
Route::get('/room/{id}/show', [App\Http\Controllers\LandingPageController::class, 'room_show'])->name('room.show');
// Route::post('room/{id}/available', [App\Http\Controllers\LandingPageController::class, 'available_rooms'])->name('landing.available_rooms');
Route::post('room/{id}/reservation', [App\Http\Controllers\LandingPageController::class, 'room_reservation_store'])->name('landing.room_reservation_store');
Route::post('room/{id}/reservation-summary', [App\Http\Controllers\LandingPageController::class, 'room_reservation_summary'])->name('landing.room_reservation_summary');

Route::get('guest-transaction/{code}', [App\Http\Controllers\LandingPageController::class, 'transaction_show'])->name('landing.transaction_show');
// Route::get('/room/{id}/book', [App\Http\Controllers\LandingPageController::class, 'room_book'])->name('room.book');
Route::post('room/{id}/getrooms_available', [App\Http\Controllers\LandingPageController::class, 'getrooms_available'])->name('landing.getrooms_available');

Route::post('room/available', [App\Http\Controllers\LandingPageController::class, 'room_available'])->name('landing.room_available');
Route::post('cottage/available', [App\Http\Controllers\LandingPageController::class, 'cottage_available'])->name('landing.cottage_available');

Route::get('/cottages', [App\Http\Controllers\LandingPageController::class, 'cottages'])->name('landing.cottages');
Route::get('/cottage/{id}/show', [App\Http\Controllers\LandingPageController::class, 'cottage_show'])->name('cottage.show');
Route::post('cottage/{id}/getcottages_available', [App\Http\Controllers\LandingPageController::class, 'getcottages_available'])->name('landing.getcottages_available');
Route::post('cottage/{id}/check_cottage_available', [App\Http\Controllers\LandingPageController::class, 'check_cottage_available'])->name('landing.check_cottage_available');
Route::post('cottage/{id}/reservation', [App\Http\Controllers\LandingPageController::class, 'cottage_reservation_store'])->name('landing.cottage_reservation_store');

Route::post('cottage/{id}/reservation-summary', [App\Http\Controllers\LandingPageController::class, 'cottage_reservation_summary'])->name('landing.cottage_reservation_summary');

Route::get('/exclusive-rental', [App\Http\Controllers\LandingPageController::class, 'exclusive_rental'])->name('landing.exclusive_rental');
Route::post('/exclusive-rental/store', [App\Http\Controllers\LandingPageController::class, 'exclusive_rental_store'])->name('landing.exclusive_rental_store');
Route::post('exclusive-rental/get_available', [App\Http\Controllers\LandingPageController::class, 'getexclusive_available'])->name('landing.getexclusive_available');

Route::post('/exclusive-rental/reservation-summary', [App\Http\Controllers\LandingPageController::class, 'exclusive_rental_summary'])->name('landing.exclusive_rental_summary');
Route::post('room-rental/get_available/{id}', [App\Http\Controllers\LandingPageController::class, 'getroom_available'])->name('landing.getroom_available');

Route::get('/contact', [App\Http\Controllers\LandingPageController::class, 'contact'])->name('landing.contact');

// Auth::routes();
Auth::routes(['register' => false]);

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
Route::put('/room-coverimage', [App\Http\Controllers\RoomController::class, 'coverimage_remove'])->name('room.coverimage.remove');

Route::resource('cottage', App\Http\Controllers\CottageController::class)->except(['index', 'show']);
Route::get('/all-cottages', [App\Http\Controllers\CottageController::class, 'index'])->name('cottage.index');
Route::get('/cottages-datatables', [App\Http\Controllers\CottageController::class, 'datatables'])->name('cottage.datatables');
Route::put('/cottage-image', [App\Http\Controllers\CottageController::class, 'image_remove'])->name('cottage.image.remove');
Route::put('/cottage-coverimage', [App\Http\Controllers\CottageController::class, 'coverimage_remove'])->name('cottage.coverimage.remove');

Route::resource('guest', App\Http\Controllers\GuestController::class)->except(['index']);
Route::get('/guests', [App\Http\Controllers\GuestController::class, 'index'])->name('guest.index');
Route::get('/guests-datatables', [App\Http\Controllers\GuestController::class, 'datatables'])->name('guest.datatables');
Route::get('/get_guests', [App\Http\Controllers\GuestController::class, 'get_guests'])->name('guest.get_guests');

Route::resource('transaction', App\Http\Controllers\TransactionController::class)->except(['index']);
Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
Route::get('/transactions-datatables', [App\Http\Controllers\TransactionController::class, 'datatables'])->name('transaction.datatables');
Route::get('/transaction/invoice/{id}', [App\Http\Controllers\TransactionController::class, 'invoice'])->name('transaction.invoice');
// Route::post('/transaction/invoice/{id}/pay', [App\Http\Controllers\TransactionController::class, 'pay'])->name('transaction.pay');
Route::put('/transaction/invoice/{id}/complete', [App\Http\Controllers\TransactionController::class, 'complete'])->name('transaction.complete');

Route::get('/transaction/guest/create', [App\Http\Controllers\TransactionController::class, 'guest_create'])->name('transaction.guest_create');
Route::get('/transaction/guest/{id}', [App\Http\Controllers\TransactionController::class, 'guest_show'])->name('transaction.guest_show');

Route::resource('reservation', App\Http\Controllers\ReservationController::class)->except(['index', 'show', 'update', 'store']);
Route::get('/reservations', [App\Http\Controllers\ReservationController::class, 'index'])->name('reservation.index');
Route::get('/reservations-datatables', [App\Http\Controllers\ReservationController::class, 'datatables'])->name('reservation.datatables');
Route::put('/reservation-confirm/{id}', [App\Http\Controllers\ReservationController::class, 'confirm'])->name('reservation.confirm');
Route::put('/reservation-cancel/{id}', [App\Http\Controllers\ReservationController::class, 'cancel'])->name('reservation.cancel');

Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
Route::get('/reports-datatables', [App\Http\Controllers\ReportController::class, 'datatables'])->name('report.datatables');

Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');

Route::resource('/settings/entrancefee', App\Http\Controllers\EntrancefeeController::class)->only(['edit', 'update']);
Route::resource('/settings/breakfast', App\Http\Controllers\BreakfastController::class)->except(['index', 'show']);
Route::resource('resort', App\Http\Controllers\ResortController::class)->only(['update']);

Route::post('/transaction/get_available_rooms_cottages', [App\Http\Controllers\TransactionController::class, 'get_available_rooms_cottages'])->name('transaction.get_available_rooms_cottages');

Route::get('/transaction/{id}/edit/cottage', [App\Http\Controllers\TransactionController::class, 'edit_cottage'])->name('transaction.edit_cottage');
Route::get('/transaction/{id}/edit/room', [App\Http\Controllers\TransactionController::class, 'edit_room'])->name('transaction.edit_room');
Route::get('/transaction/{id}/edit/exclusive', [App\Http\Controllers\TransactionController::class, 'edit_exclusive'])->name('transaction.edit_exclusive');

Route::put('/transaction/{id}/edit/cottage', [App\Http\Controllers\TransactionController::class, 'update_cottage'])->name('transaction.update_cottage');
Route::put('/transaction/{id}/edit/room', [App\Http\Controllers\TransactionController::class, 'update_room'])->name('transaction.update_room');
Route::put('/transaction/{id}/edit/exclusive', [App\Http\Controllers\TransactionController::class, 'update_exclusive'])->name('transaction.update_exclusive');
Route::post('/transaction/summary', [App\Http\Controllers\TransactionController::class, 'summary'])->name('transaction.summary');