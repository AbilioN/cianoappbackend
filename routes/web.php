<?php

// use App\Http\Controllers\Auth\Login;
// use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\AuthenticationController;
use App\Livewire\Admin;
use App\Livewire\Products;
use App\Livewire\EditProduct;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Partner\Create;
use App\Livewire\Partner\Edit;
use App\Livewire\Partner\Show;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin as AdminMiddleware;
use App\Livewire\Aquariums\Create as AquariumsCreate;
use App\Livewire\History\Show as HistoryShow;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Livewire\ShowProduct;
use App\Livewire\CreateProduct;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::middleware('web')->group(function () {
    Route::get('/register', Register::class);
    Route::get('/login', Login::class);
    Route::post('/forgot-password', [AuthenticationController::class, 'sendResetLink'])->name('auth.password.email');
    Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])->name('auth.password.update');
    Route::get('/reset-password/{token}', function ($token, Request $request) {
        $language = $request->query('language', 'en');
        return view("components.auth.reset-password-{$language}", ['token' => $token]);
    })->name('auth.password.reset');
    Route::post('/auth/register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Você está na área de administração.';
    });
});

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin', Admin::class)->name('admin.home');
    Route::get('/admin/client/create', Create::class)->name('admin.partner.create');
    Route::get('/admin/client/show', Show::class)->name('admin.partner.show');
    Route::get('/admin/client/edit/{id}', Edit::class)->name('admin.partner.edit');
    Route::get('/admin/history/{id}', HistoryShow::class)->name('history-show');
    Route::get('/admin/products', Products::class)->name('admin.products');
    Route::get('/admin/products/create', CreateProduct::class)->name('admin.products.create');
    Route::get('/admin/products/{id}/edit', EditProduct::class)->name('admin.products.edit');
    Route::get('/admin/products/{id}', ShowProduct::class)->name('admin.products.show');
    Route::get('/admin/products/drafts', function(){
        // return view('admin.products.drafts');
    })->name('admin.products.drafts');

    Route::get('/profile', function () {

        
        // ...
    })->withoutMiddleware([AdminMiddleware::class]);
    // Route::get('/admin/dashboard' , Dashboard::class)->name('admin.dashboard');


    Route::get('/products', function(){
        return view('products');
    })->name('products');
    // Route::get('/history', function(){
    //     return 'history';
    // })->name('history-show');

    // Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    Route::get('/admin/guides', \App\Livewire\Guides::class)->name('admin.guides');
    Route::get('/admin/guides/{id}', \App\Livewire\ShowGuide::class)->name('admin.guides.show');
    Route::get('/admin/guides/{id}/edit', \App\Livewire\EditGuide::class)->name('admin.guides.edit');
});
