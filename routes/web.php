<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Sets
Route::get('/sets', [\App\Http\Controllers\SetsController::class, 'sets'])->name('sets');
Route::post('/set/add', [\App\Http\Controllers\SetsController::class, 'add'])->name('set.add');
Route::post('/set/{set_id}/delete', [\App\Http\Controllers\SetsController::class, 'delete'])->name('set.delete');

// Images
Route::get('/images/{set_id}', [\App\Http\Controllers\ImagesController::class, 'images'])->name('images');
Route::post('/image/{set_id}/add', [\App\Http\Controllers\ImagesController::class, 'add'])->name('image.add');
Route::post('/image/{set_id}/delete', [\App\Http\Controllers\ImagesController::class, 'delete'])->name('image.delete');
Route::post('/image/{set_id}/update', [\App\Http\Controllers\ImagesController::class, 'update'])->name('image.update');

