<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;



Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/blogs-by-category/{id}', [CategoryController::class,'blogsByCategory'])->name('blogsByCategory');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/add',[NewsController::class,'add'])->name('add');
    Route::post('/store',[NewsController::class,'store'])->name('store');
    Route::get('/add-category',[CategoryController::class,'index'])->name('add-category');
    Route::post('/store-category',[CategoryController::class,'store'])->name('store-category');
    Route::get('/manage', [NewsController::class, "manage"])->name('manage');
    Route::get('/edit/{id}', [NewsController::class, "edit"])->name('edit');
    Route::post('/update', [NewsController::class, "update"])->name('news-update');
    Route::get('/delete/{news_id}', [NewsController::class,'delete'])->name('news.delete');
});
