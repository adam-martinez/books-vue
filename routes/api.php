<?php

use App\Http\Controllers\Api\BookListsController;
use Illuminate\Support\Facades\Route;


Route::prefix('books')
    ->group(function () {
        Route::get('lists', [BookListsController::class, 'lists'])->name('api.lists');
        Route::get('lists/{slug}', [BookListsController::class, 'list'])->name('api.list');
});

