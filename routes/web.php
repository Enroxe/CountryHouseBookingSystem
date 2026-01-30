<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingPaymentController;
use App\Models\House;

// Главная страница (Guest)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Дашборд
Route::middleware(['auth'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Авторизованные маршруты
Route::middleware(['auth'])->group(function () {

    //  ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Мои бронирования
    Route::get('/my-bookings', \App\Livewire\User\MyBookings::class)
        ->name('bookings.my');
});

// Каталог домиков (доступен всем)
Route::get('/catalog', function () {
    return view('houses.index');
})->name('houses.index');

// Страница домика (доступна всем)
Route::get('/catalog/{house}', function (House $house) {
    return view('houses.show', compact('house'));
})->name('houses.show');

// Авторизованные маршруты для бронирования
Route::middleware(['auth'])->group(function () {

    // Оплата бронирования
    Route::get('/booking/payment', [BookingPaymentController::class, 'show'])
        ->name('booking.payment');

    Route::post('/booking/payment', [BookingPaymentController::class, 'pay'])
        ->name('booking.payment.pay');

    Route::get('/booking/payment/success/{booking}', [BookingPaymentController::class, 'success'])
        ->name('booking.payment.success');

    // Админ-панель
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/houses', function () {
            return view('admin.houses');
        })->name('admin.houses');

        Route::get('/admin/bookings', function () {
            return view('admin.bookings');
        })->name('admin.bookings');

        Route::get('/admin/statistics', function () {
            return view('admin.statistics');
        })->name('admin.statistics');

    });
});

require __DIR__ . '/auth.php';
