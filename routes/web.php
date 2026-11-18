<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::view('/products-fetch', 'products.fetch')->name('products.fetch');

//  Defisini route CRUD product
Route::resource('products', ProductController::class);

// Baris diatas menghasilkan beberapa route seperti berikut:
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::post('/products', [ProductController::class, 'store'])->name('products.store');
// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::get('/products/{product:id}', [ProductController::class, 'show'])->name('products.show');
// Route::put('/products', [ProductController::class, 'store'])->name('products.update');
// Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
// Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
