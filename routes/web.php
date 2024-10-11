<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Web Auth
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// API routes
Route::prefix('api')->group(function () {
    // API Auth
    Route::post('/register', [UserController::class, 'apiRegister']);
    Route::post('/login', [UserController::class, 'apiLogin']);

    // API Middleware
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'apiLogout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/posts', [PostController::class, 'apiIndex']);
        Route::post('/posts', [PostController::class, 'apiStore']);
        Route::get('/posts/{post}', [PostController::class, 'apiShow']);
        Route::put('/posts/{post}', [PostController::class, 'apiUpdate']);
        Route::delete('/posts/{post}', [PostController::class, 'apiDestroy']);

        Route::post('/posts/{post}/comments', [CommentController::class, 'apiStore']);
        Route::delete('/comments/{comment}', [CommentController::class, 'apiDestroy']);

        Route::post('/posts/{post}/like', [LikeController::class, 'apiToggleLike']);
    });
});

// Web middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [PostController::class, 'index'])->name('home');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggleLike'])->name('posts.like');
});