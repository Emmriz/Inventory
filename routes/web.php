<?php

// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Items routes
    Route::resource('items', ItemController::class);
    
    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
        Route::get('/reports', function () {
            return view('reports');
        })->name('reports');
        Route::get('/settings', function () {
            return view('settings');
        })->name('settings');
    });
    
    // User routes
    Route::get('/my-department', function () {
        return view('my-department');
    })->name('my-department');
});

require __DIR__.'/auth.php';
