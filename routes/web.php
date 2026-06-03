<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignerRecordController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HandbookController;
use App\Http\Controllers\RecordModuleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/handbook', [HandbookController::class, 'show'])->name('handbook');
    Route::get('/modules/{module}', [RecordModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}/records/{record}/edit', [RecordModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}/records/{record}', [RecordModuleController::class, 'update'])->name('modules.update');
    Route::resource('records', DesignerRecordController::class);
    Route::post('/records/{record}/documents', [DocumentController::class, 'store'])->name('records.documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
