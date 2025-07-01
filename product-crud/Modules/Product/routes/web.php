<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Livewire\ProductList;


Route::prefix('products')->group(function() {
    Route::get('/', function() {
        return view('product::index');
    })->name('products.index');
});