<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
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
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/department/{departmentId}', [UserController::class, 'byDepartment'])->name('users.by_department');
Route::post('/users/{user}/last-login', [UserController::class, 'updateLastLogin'])->name('users.update_last_login');
// Route::put('/users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('users.update_permissions');
Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');
Route::get('/users/{user}/permissions', [UserController::class, 'getPermissions'])->name('users.getPermissions');
Route::get('/users/{user}/permissions', [UserController::class, 'getPermissions']);
Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions']);

   
});

// Reports routes - accessible by all authenticated users
Route::middleware(['auth','admin'])->prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');

    // Items reports
    Route::get('/items', [ReportController::class, 'itemsReport'])->name('reports.items');
    Route::get('/items/pdf', [ReportController::class, 'downloadItemsPDF'])->name('reports.items.pdf');

    // Departments reports
    Route::get('/departments', [ReportController::class, 'departmentsReport'])->name('reports.departments');
    Route::get('/departments/pdf', [ReportController::class, 'downloadDepartmentsPDF'])->name('reports.departments.pdf');

    Route::get('/members', [ReportController::class, 'membersReport'])->name('reports.members');
    Route::get('/members/pdf', [ReportController::class, 'downloadMembersPDF'])->name('reports.members.pdf');
});

// Borrowings routes (Admin only)
     Route::middleware('admin')->group(function () {
        Route::resource('borrowings', App\Http\Controllers\BorrowingController::class)->except(['show', 'edit', 'update']);
        Route::patch('/borrowings/{borrowing}/return', [App\Http\Controllers\BorrowingController::class, 'returnItem'])->name('borrowings.return');
        Route::get('/borrowings/items-by-department/{department}', [App\Http\Controllers\BorrowingController::class, 'getItemsByDepartment'])->name('borrowings.items-by-department');
    });
    
    // Borrowings routes (Admin only)
     Route::middleware('auth')->group(function () {
        Route::get('/members/{id}', [MemberController::class, 'show']);
        // Members routes
        Route::resource('members', MemberController::class)->only(['store', 'update', 'destroy']);
    });

    Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
});
    
// Settings routes - admin only

Route::middleware(['auth', 'admin'])->group(function () {
   Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/theme-toggle', [SettingsController::class, 'toggleTheme'])->name('theme.toggle');
Route::post('/settings/accent', [SettingsController::class, 'setAccent'])->name('accent.set');
Route::post('/settings/accent/reset', [SettingsController::class, 'resetAccent'])->name('accent.reset');

});

// Profile- accessible by all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Calendar and Events routes
Route::prefix('events')->name('events.')->middleware('admin')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/list', [EventController::class, 'getEvents'])->name('get'); // fetch events for calendar
    
    // Normal form routes
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::put('/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');

    // AJAX routes
    Route::post('/store-ajax', [EventController::class, 'storeAjax'])->name('store.ajax');
    Route::put('/{event}/update-ajax', [EventController::class, 'updateAjax'])->name('update.ajax');
    Route::delete('/{event}/delete-ajax', [EventController::class, 'destroyAjax'])->name('destroy.ajax');
});

require __DIR__.'/auth.php';

