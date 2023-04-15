<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ChapterController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\OnboardController;
use App\Http\Controllers\GetController;


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

Route::post('/login', [\App\Http\Controllers\API\AuthController::class,'login']);
Route::post('/register', [\App\Http\Controllers\API\AuthController::class,'register']);


Route::prefix('backend/')->name('backend.')->group(function(){
    Route::get('product/all',[ProductController::class,'all'])->name('product.all');
    Route::get('chapter/all',[ChapterController::class,'all'])->name('chapter.all');
    Route::get('attribute/all',[AttributeController::class,'showAll'])->name('attribute.all');
    Route::get('attribute/popular',[AttributeController::class,'popular'])->name('attribute.popular');
    Route::get('attribute/{id}',[AttributeController::class,'showId'])->name('attribute.id');
    Route::get('onboard/all',[OnboardController::class,'all'])->name('onboard.all');

    //recommended
    Route::get('product/recommended',[ProductController::class,'recommended'])->name('product.recommended');
    Route::get('product/popular',[ProductController::class,'popular'])->name('product.popular');

});
Route::post('/add/data',[GetController::class, 'addMultipleBooks']);
Route::delete('/favourite/{id}',[GetController::class,'destroy']);
Route::post('/add/comments',[GetController::class, 'comments']);
Route::post('/add/favourite',[ProductController::class, 'addFavourites']);
Route::post('/add/attribute',[AttributeController::class, 'addFavourites']);
Route::post('/add/paid',[\App\Http\Controllers\API\AuthController::class, 'addFavourites']);
Route::get('/add/comments',[GetController::class, 'getComment']);
Route::get('/getFav',[GetController::class, 'favourites']);

//likes

Route::post('/add/likes',[GetController::class, 'addLikes']);
Route::get('/show/likes',[GetController::class, 'getLikes']);

