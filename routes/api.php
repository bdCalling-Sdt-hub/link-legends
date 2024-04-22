<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RProductController;
use App\Http\Controllers\RSubCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





// -----------------Category --------------------
Route::post('add-category', [CategoryController::class, 'addCategory']);
Route::post('update-category', [CategoryController::class, 'updateCategory']);
Route::get('delete-category', [CategoryController::class, 'deleteCategory']);
Route::get('show-category', [CategoryController::class, 'showCategory']);

// -----------------Sub Category --------------------
Route::resource('sub_categories',RSubCategoryController::class)->except('create','edit');
//-----------------Product-----------------------

Route::resource('product',RProductController::class)->except('create','edit');
