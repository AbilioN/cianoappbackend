<?php

// use App\Http\Controllers\Auth\Login;
// use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\AuthenticationController;
use App\Livewire\Admin;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Partner\Create;
use App\Livewire\Partner\Edit;
use App\Livewire\Partner\Show;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin as AdminMiddleware;
use App\Livewire\Aquariums\Create as AquariumsCreate;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/register', [Register::class, 'index']);
// Route::get('/login', [Login::class, 'index']);
// Route::post('/auth/register', [AuthenticationController::class, 'register'])->name('register');
// Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('login');
// Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::get('/register', Register::class);
Route::get('/login', Login::class);
Route::post('/auth/register', [AuthenticationController::class, 'register'])->name('register');
Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Você está na área de administração.';
    });
});

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin', Admin::class)->name('admin.home');
    Route::get('/admin/partner/create', Create::class)->name('admin.partner.create');
    Route::get('/admin/partner/show', Show::class)->name('admin.partner.show');
    Route::get('/admin/partner/edit/{id}', Edit::class)->name('admin.partner.edit');
    Route::get('/admin/aquariums/create', AquariumsCreate::class)->name('admin.aquariums.create');

    Route::get('/profile', function () {
        // ...
    })->withoutMiddleware([AdminMiddleware::class]);
    Route::get('/admin/dashboard' , Dashboard::class)->name('admin.dashboard');
});
