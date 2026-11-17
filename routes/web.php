<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('products.index');
});

Route::resource('products', ProductController::class);

// Route::get('/products', [ProductController::class, 'index'])->name('products.index);
// Route::post('/products', [ProductController::class, 'store'])->name('products.store');
// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::get('/products/{product:id}', [ProductController::class, 'show'])->name('products.show');
// Route::put('/products', [ProductController::class, 'store'])->name('products.update');
// Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.store');
// Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

 