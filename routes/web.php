<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/blogs-by-category/{id}', [CategoryController::class,'blogsByCategory'])->name('blogsByCategory');

// Custom Email/Password Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'login']);
    
    Route::get('/register', [AuthenticationController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthenticationController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [AuthenticationController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthenticationController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthenticationController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});

// Social Authentication Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

Route::get('/auth/github', [SocialAuthController::class, 'redirectToGithub'])->name('auth.github');
Route::get('/auth/github/callback', [SocialAuthController::class, 'handleGithubCallback'])->name('auth.github.callback');

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