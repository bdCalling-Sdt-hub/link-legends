<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\BlogeController;
use App\Http\Controllers\Api\CarosalController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FAQRequestController;
use App\Http\Controllers\Api\PrivacyController;
use App\Http\Controllers\Api\SecandCarosalController;
use App\Http\Controllers\Api\TerandingBrandController;
use App\Http\Controllers\Api\TermsConditionController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

/*
 * Dashboard show data
 */
Route::get('/admin/notification', [DashboardController::class, 'showAdminNotifications']);
Route::get('/counting', [DashboardController::class, 'counting_dashboar']);
Route::get('/user/overview', [DashboardController::class, 'user_overview']);
Route::resource('/dashboard', DashboardController::class)->except('create');
Route::resource('/carosal', CarosalController::class)->except('create');
Route::post('/update/carosal', [CarosalController::class, 'update']);
Route::resource('/slide', SecandCarosalController::class)->except('create');
Route::resource('/tranding/brand', TerandingBrandController::class)->except('create');
Route::resource('/about', AboutController::class)->except('create');
Route::resource('/blog', BlogeController::class)->except('create');
Route::post('/blog/update/status/{id}', [BlogeController::class, 'update_status']);
Route::resource('/help/line', ContactController::class)->except('create');
Route::resource('/faq', FAQRequestController::class)->except('create');
Route::resource('/terms/conditions', TermsConditionController::class)->except('create');
Route::resource('/privacy', PrivacyController::class)->except('create');

Route::middleware(['user', 'auth:api'])->group(function () {
    Route::resource('ratings', RRatingController::class)->except('create', 'edit');
});

Route::get('/publish-rating/{id}', [RRatingController::class, 'publishRating']);

Route::middleware(['admin', 'auth:api'])->group(function () {});

// -----------------Category --------------------
Route::post('add-category', [CategoryController::class, 'addCategory']);
Route::post('update-category', [CategoryController::class, 'updateCategory']);
Route::get('delete-category', [CategoryController::class, 'deleteCategory']);
Route::get('show-category', [CategoryController::class, 'showCategory']);

// -----------------Sub Category --------------------
Route::resource('sub_categories', RSubCategoryController::class)->except('create', 'edit');

// -----------------Product--------------------------

Route::resource('product', RProductController::class)->except('create', 'edit');

// ------------------Rating---------------------------

// ----------------Contact --------------------------
Route::resource('contact', RContactController::class)->except('create', 'edit');

Route::middleware(['super.admin', 'auth:api'])->group(function () {
    // super admin
    Route::post('/add-admin', [AuthAdminController::class, 'addAdmin']);
    Route::get('/show-admin', [AuthAdminController::class, 'showAdmin']);
    Route::get('/delete-admin/{id}', [AuthAdminController::class, 'deleteAdmin']);
});

// public api for website
Route::get('/our-picks', [WebsiteController::class, 'ourPicks']);
