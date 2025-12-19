<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IssController;
use App\Http\Controllers\OsdrController;
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

Route::prefix('dashboard')->group(function () {
    
    Route::get('/iss', [IssController::class, 'index'])->name('dashboard.iss');
    
    Route::get('/osdr', function() {
        return view('dashboard.osdr');
    })->name('dashboard.osdr');
    
});