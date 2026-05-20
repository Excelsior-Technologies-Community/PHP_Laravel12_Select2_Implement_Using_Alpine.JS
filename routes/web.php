<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/suggestions', [ProductController::class, 'getSuggestions'])->name('products.suggestions');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products-trash', [ProductController::class, 'trash'])->name('products.trash');
Route::get('/products-restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
Route::delete('/products-force-delete/{id}', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
Route::post('/products/remove-image', [ProductController::class, 'removeImage'])->name('products.removeImage');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');