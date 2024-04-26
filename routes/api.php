<?php

use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookMarkController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\RCategoryController;
use App\Http\Controllers\RContactController;
use App\Http\Controllers\RProductController;
use App\Http\Controllers\RRatingController;
use App\Http\Controllers\RSubCategoryController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    ['middleware' => 'auth:api']
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/email-verified', [AuthController::class, 'emailVerified']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'loggedUserData']);
    Route::post('/forget-pass', [AuthController::class, 'forgetPassword']);
    Route::post('/verified-checker', [AuthController::class, 'emailVerifiedForResetPass']);
    Route::post('/reset-pass', [AuthController::class, 'resetPassword']);
    Route::post('/update-pass', [AuthController::class, 'updatePassword']);
    Route::put('/profile/edit/{id}', [AuthController::class, 'editProfile']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
});


Route::middleware(['user','auth:api'])->group(function (){
    Route::get('/toggle-bookmark',[BookMarkController::class,'toggleBookmark']);
    Route::get('/wish-list',[BookMarkController::class,'wishListData']);
});

Route::middleware(['admin.guest'])->group(function (){
    Route::resource('categories',RCategoryController::class)->only('index');
    Route::resource('products',RProductController::class)->only('show');
});


Route::middleware(['admin','auth:api'])->group(function (){

    //-----------------Product--------------------------
    Route::resource('products',RProductController::class)->except('create','edit','show');
    Route::get('user-list',[DashboardUserController::class,'allUser']);
    Route::get('/publish-rating/{id}',[RRatingController::class,'publishRating']);
    Route::resource('ratings',RRatingController::class)->only('index');

    // -----------------Category --------------------

    Route::resource('categories',RCategoryController::class)->except('create','edit','index');

// -----------------Sub Category --------------------
    Route::resource('sub_categories',RSubCategoryController::class)->except('create','edit');
});

//------------------Rating---------------------------

// ----------------Contact --------------------------
Route::resource('contact',RContactController::class)->except('create','edit');

Route::middleware(['super.admin', 'auth:api'])->group(function () {
    // super admin
    Route::post('/add-admin', [AuthAdminController::class, 'addAdmin']);
    Route::get('/show-admin', [AuthAdminController::class, 'showAdmin']);
    Route::get('/delete-admin/{id}', [AuthAdminController::class, 'deleteAdmin']);

});

//public api's
Route::get('/our-picks',[WebsiteController::class,'ourPicks']);
Route::get('/trending-products',[WebsiteController::class,'trendingProducts']);
Route::resource('ratings',RRatingController::class)->except('create','edit','index');

Route::get('/customer-review',[RRatingController::class,'customerReview']);
