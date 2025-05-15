<?php

use Example\TranscarpatianFood\Http\Controllers\CartController;
use Example\TranscarpatianFood\Http\Controllers\CategoryController;
use Example\TranscarpatianFood\Http\Controllers\OrderController;
use Example\TranscarpatianFood\Http\Controllers\ProductController;
use Example\TranscarpatianFood\Http\Controllers\ProfileController;
use Example\TranscarpatianFood\Http\Controllers\SearchController;
use Example\TranscarpatianFood\Http\Controllers\Admin\OrderController as AdminOrderController;
use Example\TranscarpatianFood\Models\Category;
use Example\TranscarpatianFood\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = Category::all();
    $popularProducts = Product::orderBy('popularity', 'desc')->take(8)->get();
    return view('welcome', compact('categories', 'popularProducts'));
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Профіль користувача
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/search', [SearchController::class, 'index'])->name('search');

// Категорії
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Список товарів
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Окрема сторінка товару
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Кошик
Route::prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Замовлення
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/process', [OrderController::class, 'process'])->name('order.process');
Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Адмін-панель (доступна тільки для адміністраторів)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Панель приладів
    Route::get('/', [\Example\TranscarpatianFood\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Управління замовленнями
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Управління товарами
    Route::get('/products', [\Example\TranscarpatianFood\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\Example\TranscarpatianFood\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\Example\TranscarpatianFood\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\Example\TranscarpatianFood\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\Example\TranscarpatianFood\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\Example\TranscarpatianFood\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Управління категоріями
    Route::get('/categories', [\Example\TranscarpatianFood\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [\Example\TranscarpatianFood\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [\Example\TranscarpatianFood\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [\Example\TranscarpatianFood\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [\Example\TranscarpatianFood\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\Example\TranscarpatianFood\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Управління користувачами
    Route::get('/users', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/password', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');
    Route::delete('/users/{user}', [\Example\TranscarpatianFood\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
