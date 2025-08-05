<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Auth;

// Authentication routes (Laravel Breeze/UI handles these)
Auth::routes();

// Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Members routes
    Route::resource('members', MemberController::class)->only(['store', 'update', 'destroy']);

// Items routes - accessible by all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');


    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/department/create',[DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/department/store',[DepartmentController::class, 'store'])->name('departments.store')->middleware('admin');
    // Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('admin');
    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('admin');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('admin');
});

// Admin-only routes
Route::middleware(['auth'])->group(function () {

        
   // Users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/department/{departmentId}', [UserController::class, 'byDepartment'])->name('users.by_department');
Route::post('/users/{id}/last-login', [UserController::class, 'updateLastLogin'])->name('users.update_last_login');
// Route::put('/users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('users.update_permissions');
Route::put('/users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');
Route::get('/users/{id}/permissions', [UserController::class, 'getPermissions'])->name('users.getPermissions');
   
});

// Reports routes - accessible by all authenticated users
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    Route::get('/reports/department/{department}', [ReportController::class, 'department'])->name('reports.department');
});

// Borrowings routes (Admin only)
     Route::middleware('admin')->group(function () {
        Route::resource('borrowings', App\Http\Controllers\BorrowingController::class)->except(['show', 'edit', 'update']);
        Route::patch('/borrowings/{borrowing}/return', [App\Http\Controllers\BorrowingController::class, 'returnItem'])->name('borrowings.return');
        Route::get('/borrowings/items-by-department/{department}', [App\Http\Controllers\BorrowingController::class, 'getItemsByDepartment'])->name('borrowings.items-by-department');
    });
    
    // Borrowings routes (Admin only)
     Route::middleware('admin')->group(function () {
        Route::get('/members/{id}', [MemberController::class, 'show']);
    });
    
// Settings routes - admin only

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Profile- accessible by all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

