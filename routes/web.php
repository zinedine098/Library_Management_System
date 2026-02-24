<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Change Password
    Route::get('change-password', [PasswordController::class, 'edit'])
        ->name('change-password.edit');
    Route::put('change-password', [PasswordController::class, 'update'])
        ->name('change-password.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Books
    Route::resource('books', BookController::class);

    // Members
    Route::resource('members', MemberController::class);
    Route::post('members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])
        ->name('members.toggle-status');

    // Borrowings
    Route::get('borrowings/export', [BorrowingController::class, 'export'])
        ->name('borrowings.export');
    Route::get('borrowings/{borrowing}/return', [BorrowingController::class, 'returnForm'])
        ->name('borrowings.return-form');
    Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])
        ->name('borrowings.process-return');
    Route::resource('borrowings', BorrowingController::class);

    // Redirect root to dashboard
    Route::redirect('/', '/dashboard');
});
