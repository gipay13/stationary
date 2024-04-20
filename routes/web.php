<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Invokable\SearchProduct;
use App\Http\Controllers\Invokable\UserWithSupervisorRole;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StationaryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Category
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::patch('/category/edit', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::patch('/supplier/edit', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Supplier
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::patch('/product/edit', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product', [ProductController::class, 'destroy'])->name('product.destroy');

    // User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/edit', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');

    // Pengajuan
    Route::get('/stationary', [StationaryController::class, 'index'])->name('stationary.index');
    Route::get('/stationary/create', [StationaryController::class, 'create'])->name('stationary.create');
    Route::post('/stationary/create', [StationaryController::class, 'store'])->name('stationary.store');
    Route::get('/stationary/{id}/show', [StationaryController::class, 'show'])->name('stationary.show');
    Route::patch('/stationary/{id}/show', [StationaryController::class, 'update']);

    // Invokable Controller
    Route::get('/get-product', SearchProduct::class)->name('get-product');
    Route::get('/get-user-supervisor', UserWithSupervisorRole::class)->name('get-user-supervisor');
});

require __DIR__.'/auth.php';
