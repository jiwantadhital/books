<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('backend/')->name('backend.')->group(function(){
    Route::get('/backend/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
Route::prefix('backend/')->name('backend.')->group(function(){
 //category
 Route::post('category/getSubcategoryByCategoryId',[CategoryController::class,'getSubcategoryByCategoryId'])->name('category.getSubcategory');
 Route::get('category/trash', [CategoryController::class,'trash'])->name('category.trash');
 Route::post('category/{id}/restore', [CategoryController::class,'restore'])->name('category.restore');
 Route::delete('category/{id}/force-delete',[CategoryController::class,'forceDelete'])->name('category.forceDelete');
 Route::resource('category',CategoryController::class);
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/changePassword',[App\Http\Controllers\HomeController::class, 'showChangePasswordGet'])->name('changePasswordGet');
    Route::post('/changePassword',[App\Http\Controllers\HomeController::class, 'changePasswordPost'])->name('changePasswordPost');
});
Route::post('/mregister', [APIController::class, 'register']);