<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/* ── Public ────────────────────────────────────────────── */

Route::get('/', function () {
    return view('home');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

/* ── Student (Auth Required) ───────────────────────────── */

Route::middleware(['auth.session'])->group(function () {

    Route::get('/student', [StudentController::class, 'home'])->name('student');
    Route::get('/tutor', [StudentController::class, 'tutorList'])->name('tutor');
    Route::post('/tutor/order', [StudentController::class, 'order'])->name('tutor.order');
    Route::get('/purchase', [StudentController::class, 'purchase'])->name('purchase');

});

/* ── Admin (Auth + Role Required) ─────────────────────── */

Route::middleware(['auth.session', 'role:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'home'])->name('admin');

    Route::get('/admin/user', [AdminController::class, 'userList'])->name('admin.user');
    Route::post('/admin/user', [AdminController::class, 'storeUser'])->name('admin.user.store');
    Route::put('/admin/user/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    Route::get('/admin/assignment', [AdminController::class, 'assignmentList'])->name('admin.assignment');
    Route::post('/admin/assignment', [AdminController::class, 'storeAssignment'])->name('admin.assignment.store');
    Route::delete('/admin/assignment/{id}', [AdminController::class, 'deleteAssignment'])->name('admin.assignment.delete');

});
