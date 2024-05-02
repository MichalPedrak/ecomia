<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductUserController;
use App\Models\DataCollector;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});
Route::get('/obserwowane', [ProductController::class, 'watched'])->name('product.watched');
Route::get('/produkty', [ProductController::class, 'index'])->name('product.index');
Route::get('/produkty/kategoria/{category}/{subcategory?}/{subsubcategory?}', [ProductController::class, 'category'])
    ->name('product.category');
Route::get('/produkty/import', [ProductController::class, 'import'])->name('product.import');
Route::get('/produkty/collect', [DataCollector::class, 'collect'])->name('data_collector.collect');
Route::get('/produkty/{ean}', [ProductController::class, 'show'])->name('product.show');
Route::get('/produkty/{ean}/period/{period}', [ProductController::class, 'period'])->name('product.period');



Route::post('/produkty/obserwowane/add/{id}', [ProductUserController::class, 'add'])->name('productUser.add');
Route::post('/produkty/obserwowane/destroy/{id}', [ProductUserController::class, 'destroy'])->name('productUser.destroy');


require __DIR__.'/auth.php';
