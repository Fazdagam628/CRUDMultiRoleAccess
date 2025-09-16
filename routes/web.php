<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CandidateController;


Route::redirect('/', '/login');
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Posts
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/admin/dashboard', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/votes/results', [VoteController::class, 'results'])->name('admin.votes.results');
    Route::get('/admin/votes/results/data', [VoteController::class, 'getData'])->name('admin.votes.getData');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/admin/import', [UserController::class, 'import'])->name('users.import');
    Route::post('/admin/vote/reset', [VoteController::class, 'reset'])->name('admin.vote.reset');
    Route::post('/admin/vote/reset-all', [VoteController::class, 'resetAll'])->name('admin.vote.resetAll');
    Route::post('/admin/tokens', [TokenController::class, 'store'])->name('users.token');
    Route::get('/admin/candidates', [CandidateController::class, 'index'])->name('admin.candidates.index');
    Route::get('/admin/candidates/create', [CandidateController::class, 'create'])->name('admin.candidates.create');
    Route::get('/admin/candidates/edit/{id}', [CandidateController::class, 'edit'])->name('admin.candidates.edit');
    Route::put('/admin/candidates/update/{id}', [CandidateController::class, 'update'])->name('admin.candidates.update');
    Route::delete('/admin/candidates/delete/{id}', [CandidateController::class, 'delete'])->name('admin.candidates.delete');
    Route::post('/admin/candidates/create', [CandidateController::class, 'store'])->name('admin.candidates.store');
});
// User
Route::middleware(['auth', 'role:user'])->group(function () {
    // halaman verifikasi token
    Route::get('/user/token', fn() => view('user.token'))->name('user.token');
    Route::post('/user/token/submit', [AuthController::class, 'token'])->name('user.token.submit');

    // semua route user selain /user/token harus lewat token.verified
    Route::middleware(['token.verified'])->group(function () {
        Route::get('/user/dashboard', fn() => view('user.dashboard'))->name('user.dashboard');
        Route::post('/user/dashboard/used', [TokenController::class, 'markAsUsed'])->name('user.dashboard.used');
        Route::get('/user/vote', [VoteController::class, 'index'])->name('user.vote.index');
        Route::post('/user/vote', [VoteController::class, 'store'])->name('user.vote.store');
        // contoh: kalau user punya post sendiri
        // Route::resource('/user/posts', \App\Http\Controllers\PostController::class);
    });
});
