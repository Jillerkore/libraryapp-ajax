<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('library/books/all', [LibraryController::class, 'books'])->name('api.books.all');
Route::get('library/books/individual', [LibraryController::class, 'book'])->name('api.books.individual');
Route::post('library/books/delete', [LibraryController::class, 'removeBook'])->name('api.books.remove');
Route::post('library/books/create', [LibraryController::class, 'createBook'])->name('api.books.create');
Route::post('library/books/edit', [LibraryController::class, 'editBook'])->name('api.books.edit');
Route::get('library/books/filter', [LibraryController::class, 'filter'])->name('api.books.filter');




