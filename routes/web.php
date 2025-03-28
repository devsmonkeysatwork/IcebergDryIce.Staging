<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryPageController;
use App\Http\Controllers\Admin\WarehouseSaleCrudController;


// routes/web.php
Route::get('/', [App\Http\Controllers\WebsiteController::class, 'index'])->name('home');

Route::get('/dryice_uses', function () {
    return view('website.dryice.dryice_uses');
});
Route::get('/dryice_safety', function () {
    return view('website.dryice.safety');
});
Route::get('/blasting_info', function () {
    return view('website.blasting.blasting_info');
});
Route::get('/blasting_examples', function () {
    return view('website.blasting.blasting_example');
});
Route::get('/blasting_manuals', function () {
    return view('website.blasting.blasting_manuals');
});
Route::get('/blasting_services', function () {
    return view('website.blasting.blasting_services');
});

Route::get('/contact', [App\Http\Controllers\WebsiteController::class, 'contact'])->name('contact');

Route::get('/order', [App\Http\Controllers\WebsiteController::class, 'order'])->name('order');

Route::get('/location', [App\Http\Controllers\WebsiteController::class, 'location'])->name('location');

Route::get('/review', [App\Http\Controllers\WebsiteController::class, 'review'])->name('review');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {

    // Users
    Route::crud('users', 'UserCrudController');
    Route::crud('roles', 'RoleCrudController');
    Route::crud('permissions', 'PermissionCrudController');

    // Orders
    Route::crud('orders', 'OrderCrudController');

    // Lists
    Route::crud('postal-codes', 'PostalCodeCrudController');
    Route::crud('one-off-orders', 'OneOffOrdersCrudController');
    Route::crud('ice-orders', 'IceOrdersCrudController');
    Route::crud('variables', 'VariablesCrudController');
    Route::crud('customers', 'CustomersCrudController');
    Route::crud('log-files', 'LogFilesCrudController');

    // Reports
    Route::get('/admin/inventory', [InventoryPageController::class, 'index'])->name('admin.inventory');
    Route::crud('inventory', 'InventoryCrudController');
    Route::get('/admin/warehouse-sales', [WarehouseSaleCrudController::class, 'index'])->name('admin.warehouse_sales');
    Route::crud('warehouse-sales', 'WarehouseSaleCrudController');
});

// require __DIR__ . '/auth.php';
