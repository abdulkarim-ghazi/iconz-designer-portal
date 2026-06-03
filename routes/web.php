<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignerController;
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
    Route::get('/modules/{module}/records/{record}/edit', [RecordModuleController::class, 'edit'])->name('modules.edit');
    Route::get('/modules/{module}', [RecordModuleController::class, 'index'])->name('modules.index');
    Route::get('/designers', [DesignerController::class, 'index'])->name('designers.index');
    Route::get('/records', [DesignerRecordController::class, 'index'])->name('records.index');

    Route::middleware('design_manager')->group(function () {
        Route::get('/designers/create', [DesignerController::class, 'create'])->name('designers.create');
        Route::post('/designers', [DesignerController::class, 'store'])->name('designers.store');
        Route::get('/designers/{designer}/edit', [DesignerController::class, 'edit'])->name('designers.edit');
        Route::put('/designers/{designer}', [DesignerController::class, 'update'])->name('designers.update');
        Route::delete('/designers/{designer}', [DesignerController::class, 'destroy'])->name('designers.destroy');
        Route::get('/records/create', [DesignerRecordController::class, 'create'])->name('records.create');
        Route::post('/records', [DesignerRecordController::class, 'store'])->name('records.store');
        Route::get('/records/{record}/edit', [DesignerRecordController::class, 'edit'])->name('records.edit');
        Route::put('/records/{record}', [DesignerRecordController::class, 'update'])->name('records.update');
        Route::delete('/records/{record}', [DesignerRecordController::class, 'destroy'])->name('records.destroy');
        Route::put('/modules/{module}/records/{record}', [RecordModuleController::class, 'update'])->name('modules.update');
        Route::post('/records/{record}/documents', [DocumentController::class, 'store'])->name('records.documents.store');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    Route::get('/designers/{designer}', [DesignerController::class, 'show'])->name('designers.show');
    Route::get('/records/{record}', [DesignerRecordController::class, 'show'])->name('records.show');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
