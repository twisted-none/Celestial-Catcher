<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IssController;
use App\Http\Controllers\OsdrController; // Заглушка для второго контекста
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь мы разделяем логику на контексты (Iss, Osdr и т.д.)
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard.iss');
});

// Группа маршрутов дашборда
Route::prefix('dashboard')->group(function () {
    
    // Контекст 1: ISS (Космическая станция)
    Route::get('/iss', [IssController::class, 'index'])->name('dashboard.iss');
    
    // Контекст 2: OSDR (Научные данные - заглушка)
    Route::get('/osdr', function() {
        return view('dashboard.osdr'); // Простая вьюха
    })->name('dashboard.osdr');
    
});