<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\BlogeController;
use App\Http\Controllers\Api\CarosalController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FAQRequestController;
use App\Http\Controllers\Api\PrivacyController;
use App\Http\Controllers\Api\SecandCarosalController;
use App\Http\Controllers\Api\TerandingBrandController;
use App\Http\Controllers\Api\TermsConditionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/carosal', CarosalController::class)->except('create');
Route::post('/update/carosal', [CarosalController::class, 'update']);

Route::resource('/slide', SecandCarosalController::class)->except('create');

Route::resource('/tranding/brand', TerandingBrandController::class)->except('create');
Route::resource('/about', AboutController::class)->except('create');
Route::resource('/blog', BlogeController::class)->except('create');
Route::post('/blog/update/status/{id}', [BlogeController::class, 'update_status']);

Route::resource('/contact', ContactController::class)->except('create');

Route::resource('/faq', FAQRequestController::class)->except('create');

Route::resource('/terms/conditions', TermsConditionController::class)->except('create');
Route::resource('/privacy', PrivacyController::class)->except('create');
