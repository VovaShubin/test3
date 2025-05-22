<?php

    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\ProductController;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;

Route::get('/status/{id}', [OrderController::class, 'status'])->name('status');
Route::resource('product', ProductController::class);
Route::resource('order', OrderController::class);


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
