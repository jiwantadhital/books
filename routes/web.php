<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\NovelController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\ChapterController;
use App\Http\Controllers\Backend\ModuleController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\OnboardController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\PushNotificationController;


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
Route::post('/register', [\App\Http\Controllers\API\AuthController::class,'register']);

Auth::routes();

Route::get('/home', [DashboardController::class, 'index'])->name('home');
Route::prefix('backend/')->name('backend.')->group(function(){
    Route::get('/backend/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
Route::prefix('backend/')->name('backend.')->group(function(){


 Route::post('product/getAllAttribute',[ProductController::class,'getAllAttribute'])->name('product.getAllAttribute');
    Route::post('product/changeStatusById',[ProductController::class,'changeStatusById'])->name('product.changeStatus');
    Route::post('chapter/changeStatusById',[ChapterController::class,'changeStatusById'])->name('chapter.changeStatus');

    Route::post('product/getDistrictByProvinceId',[ProductController::class,'getDistrictByProvinceId'])->name('product.getFavourite');
    Route::post('product/getChapterByNovelId',[ProductController::class,'getChapterByNovelId'])->name('product.getChapter');
    Route::get('product/trash', [ProductController::class,'trash'])->name('product.trash');
    Route::post('product/{id}/restore', [ProductController::class,'restore'])->name('product.restore');
    Route::delete('product/{id}/force-delete',[ProductController::class,'forceDelete'])->name('product.forceDelete');
    Route::resource('product',ProductController::class);
    Route::get('product/active/{id}', [ProductController::class,'active'])->name('product.active');
    Route::get('product/deactivate/{id}', [ProductController::class,'deactive'])->name('product.deactive');
    Route::get('product/recommendedon/{id}', [ProductController::class,'recommendedon'])->name('product.recommendedon');
    Route::get('product/recommendedoff/{id}', [ProductController::class,'recommendedoff'])->name('product.recommendedoff');
    Route::get('product/flashon/{id}', [ProductController::class,'flashon'])->name('product.flashon');
    Route::get('product/flashoff/{id}', [ProductController::class,'flashoff'])->name('product.flashoff');

    Route::get('attribute/trash', [AttributeController::class,'trash'])->name('attribute.trash');
    Route::post('attribute/{id}/restore', [AttributeController::class,'restore'])->name('attribute.restore');
    Route::delete('attribute/{id}/force-delete',[AttributeController::class,'forceDelete'])->name('attribute.forceDelete');
    Route::resource('attribute',AttributeController::class);

    Route::get('chapter/trash', [ChapterController::class,'trash'])->name('chapter.trash');
    Route::post('chapter/{id}/restore', [ChapterController::class,'restore'])->name('chapter.restore');
    Route::delete('chapter/{id}/force-delete',[ChapterController::class,'forceDelete'])->name('chapter.forceDelete');
    Route::resource('chapter',ChapterController::class);

    Route::get('onboard/trash', [OnboardController::class,'trash'])->name('onboard.trash');
    Route::post('onboard/{id}/restore', [OnboardController::class,'restore'])->name('onboard.restore');
    Route::delete('onboard/{id}/force-delete',[OnboardController::class,'forceDelete'])->name('onboard.forceDelete');
    Route::resource('onboard',OnboardController::class);

    //Role
    Route::get('role/assign_permission/{role_id}', [RoleController::class,'assignPermission'])->name('role.assign_permission');
    Route::post('role/assign_permission', [RoleController::class,'postPermission'])->name('role.post_permission');

    Route::get('role/trash', [RoleController::class,'trash'])->name('role.trash');
    Route::post('role/{id}/restore', [RoleController::class,'restore'])->name('role.restore');
    Route::delete('role/{id}/force-delete',[RoleController::class,'forceDelete'])->name('role.forceDelete');
    Route::resource('role',RoleController::class);

    //Module
    Route::get('module/trash', [ModuleController::class,'trash'])->name('module.trash');
    Route::post('module/{id}/restore', [ModuleController::class,'restore'])->name('module.restore');
    Route::delete('module/{id}/force-delete',[ModuleController::class,'forceDelete'])->name('module.forceDelete');
    Route::resource('module',ModuleController::class);

    //Permission
    Route::get('permission/trash', [PermissionController::class,'trash'])->name('permission.trash');
    Route::post('permission/{id}/restore', [PermissionController::class,'restore'])->name('permission.restore');
    Route::delete('permission/{id}/force-delete',[PermissionController::class,'forceDelete'])->name('permission.forceDelete');
    Route::resource('permission',PermissionController::class);
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/changePassword',[App\Http\Controllers\HomeController::class, 'showChangePasswordGet'])->name('changePasswordGet');
    Route::post('/changePassword',[App\Http\Controllers\HomeController::class, 'changePasswordPost'])->name('changePasswordPost');
});
Route::post('/mregister', [APIController::class, 'register']);

// Notification Controllers
Route::post('send',[PushNotificationController::class, 'bulksend'])->name('bulksend');
Route::get('all-notifications', [PushNotificationController::class, 'index']);
Route::get('get-notification-form', [PushNotificationController::class, 'create']);

Route::delete('chapter/{id}/force-delete',[GetController::class,'forceDelete'])->name('chapter.forceDelete');
Route::get('got_data', [PushNotificationController::class, 'index']);