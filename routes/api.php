<?php

use App\Http\Controllers\Api\Dashboard\CategoryController;
use App\Http\Controllers\Api\Dashboard\ContactController;
use App\Http\Controllers\Api\Dashboard\FeatureController;
use App\Http\Controllers\Api\Dashboard\FormRequestController as DashboardFormRequestController;
use App\Http\Controllers\Api\Dashboard\OurServiceController;
use App\Http\Controllers\Api\Dashboard\PartnerOfSuccessController;
use App\Http\Controllers\Api\Dashboard\PlanController;
use App\Http\Controllers\Api\Dashboard\SectionController;
use App\Http\Controllers\Api\Dashboard\SocialMediaController;
use App\Http\Controllers\Api\Website\CartController;
use App\Http\Controllers\Api\Website\ContactController as WebsiteContactController;
use App\Http\Controllers\Api\Website\FeatureController as WebsiteFeatureController;
use App\Http\Controllers\Api\Website\FormRequestController;
use App\Http\Controllers\Api\Website\OurServiceController as WebsiteOurServiceController;
use App\Http\Controllers\Api\Website\PartnerOfSuccessController as WebsitePartnerOfSuccessController;
use App\Http\Controllers\Api\Website\PlanController as WebsitePlanController;
use App\Http\Controllers\Api\Website\SectionController as WebsiteSectionController;
use App\Http\Controllers\Api\Website\SocialMediaController as WebsiteSocialMediaController;
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
Route::group(['prefix' => 'dashboard'], function () {

    // sections
    Route::get('sections/get-sections', [SectionController::class, 'index']);

    Route::get('sections/get-section/{id}', [SectionController::class, 'show']);

    Route::post('sections/create', [SectionController::class, 'store']);

    Route::post('sections/update/{id}', [SectionController::class, 'update']);

    Route::post('sections/destroy/{id}', [SectionController::class, 'destroy']);



    // categories
    Route::get('categories/get-categories', [CategoryController::class, 'index']);

    Route::post('categories/create', [CategoryController::class, 'store']);

    Route::post('categories/create-all', [CategoryController::class, 'storeSeveralCategories']);

    Route::post('categories/update-all', [CategoryController::class, 'updateSeveralCategories']);

    Route::post('categories/update/{id}', [CategoryController::class, 'update']);

    Route::post('categories/destroy/{id}', [CategoryController::class, 'destroy']);

    // pricing plans
    Route::get('plans/get-plans', [PlanController::class, 'index']);

    Route::post('plans/create', [PlanController::class, 'store']);

    Route::post('plans/create-all', [PlanController::class, 'storeSeveralPlans']);

    Route::post('plans/update-all', [PlanController::class, 'updateSeveralPlans']);

    Route::post('plans/update/{id}', [PlanController::class, 'update']);

    Route::post('plans/destroy/{id}', [PlanController::class, 'destroy']);


    // Feature
    Route::get('features/get-features', [FeatureController::class, 'index']);

    Route::post('features/create', [FeatureController::class, 'store']);

    Route::post('features/create-all', [FeatureController::class, 'storeSeveralFeatures']);

    Route::post('features/update-all', [FeatureController::class, 'updateSeveralFeatures']);

    Route::post('features/update/{id}', [FeatureController::class, 'update']);

    Route::post('features/destroy/{id}', [FeatureController::class, 'destroy']);

    // partners-of-success
    Route::get('partners-of-success/get-all', [PartnerOfSuccessController::class, 'index']);

    Route::post('partners-of-success/create', [PartnerOfSuccessController::class, 'store']);

    Route::post('partners-of-success/create-all', [PartnerOfSuccessController::class, 'storeSeveralPartners']);

    Route::post('partners-of-success/update-all', [PartnerOfSuccessController::class, 'updateSeveralPartners']);

    Route::post('partners-of-success/update/{id}', [PartnerOfSuccessController::class, 'update']);

    Route::post('partners-of-success/destroy/{id}', [PartnerOfSuccessController::class, 'destroy']);

    // Form Request
    Route::get('form-request', [DashboardFormRequestController::class, 'index']);


    //contacts
    Route::apiResource('contacts', ContactController::class);

    //social media
    Route::apiResource('social-media', SocialMediaController::class);


    // our services
    Route::get('/our-services/get-all', [OurServiceController::class, 'index']);

    Route::post('/our-services/create', [OurServiceController::class, 'store']);
    Route::post('our-services/create-all', [OurServiceController::class, 'createSeveralServices']);

    Route::post('/our-services/update/{id}', [OurServiceController::class, 'update']);
    Route::post('our-services/update-all', [OurServiceController::class, 'updateSeveralServices']);

    Route::post('/our-services/destroy/{id}', [OurServiceController::class, 'destroy']);
});




Route::group(['prefix' => 'website'], function () {

    Route::get('partners-of-success/get-all', [WebsitePartnerOfSuccessController::class, 'index']);

    Route::get('social-media', [WebsiteSocialMediaController::class, 'index']);

    Route::get('contacts', [WebsiteContactController::class, 'index']);

    Route::get('plans/get-plans', [WebsitePlanController::class, 'index']);

    Route::get('features/get-features', [WebsiteFeatureController::class, 'index']);


    Route::post('form-request', [FormRequestController::class, 'store']);

    // sections
    Route::get('sections/get-sections', [WebsiteSectionController::class, 'index']);

    Route::get('sections/get-section/{sec_name}', [WebsiteSectionController::class, 'getSectionByName']);

    // services cart
    Route::post('cart/add', [CartController::class, 'add']);

    Route::get('cart/view', [CartController::class, 'view']);

    Route::post('cart/remove/{id}', [CartController::class, 'remove']);

    Route::post('/cart/whatsapp-link', [CartController::class, 'generateWhatsAppLink']);

        // our services
    Route::get('/our-services/get-all', [WebsiteOurServiceController::class, 'index']);

    // plans on whatsapp
    Route::post('/generate-plan-whatsapp-link', [WebsitePlanController::class, 'generateWhatsAppLink']);

});
