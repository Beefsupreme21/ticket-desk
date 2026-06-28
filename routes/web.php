<?php

use App\Http\Controllers\DemoErrorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('tickets/accept-next', [TicketController::class, 'acceptNext'])
        ->name('tickets.accept-next');

    Route::post('tickets/{ticket}/complete', [TicketController::class, 'complete'])
        ->name('tickets.complete');

    Route::resource('tickets', TicketController::class)->except(['destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        Route::prefix('demo/errors')->name('demo.errors.')->group(function () {
            Route::post('exception', [DemoErrorController::class, 'exception'])->name('exception');
            Route::post('failed-job', [DemoErrorController::class, 'failedJob'])->name('failed-job');
            Route::post('webhook', [DemoErrorController::class, 'webhook'])->name('webhook');
            Route::post('missing-assignee', [DemoErrorController::class, 'missingAssignee'])->name('missing-assignee');
        });
    });
});

require __DIR__.'/auth.php';
