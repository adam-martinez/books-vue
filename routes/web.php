<?php

use App\Http\Controllers\BooksController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BooksController::class, 'lists'])->name('lists');
Route::get('/{list}', [BooksController::class, 'list'])->name('list');
