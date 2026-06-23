<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::resource('posts', PostController::class);

Route::get('/lang/{locale}', function ($locale) {

    session()->put('locale', $locale);

    return redirect()->route('posts.index');

})->name('lang.switch');

Route::get('/posts-export-excel', [PostController::class, 'exportExcel'])->name('posts.export.excel');