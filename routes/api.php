<?php

use App\Http\Controllers\Api\AthleteLicenseIndividualsController;
use App\Http\Controllers\Api\CertificationAttributedApiController;
use App\Http\Controllers\Api\EventsApiController;
use App\Http\Controllers\Api\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('check.api_token', 'throttle:20,1')->group(function () {

    // Athlete lookup endpoint (external integration)
    Route::get('/individuals/athletes', [AthleteLicenseIndividualsController::class, 'index'])
        ->name('api.individuals.athletes');

    // Certifications API
    Route::get('/certifications/{code}', [CertificationAttributedApiController::class, 'show'])
        ->name('api.certifications.show');

    // Events API
    Route::get('/events/competitions', [EventsApiController::class, 'getCompetitionEvents'])
        ->name('api.events.competitions');
});

// Payment webhooks
// Rate limited to 60 requests per minute per IP to prevent abuse
// while allowing for legitimate webhook retries
Route::prefix('payment')->middleware('throttle:60,1')->group(function () {
    Route::prefix('webhook')->group(function () {
        Route::post('easypay', [PaymentWebhookController::class, 'easypay'])->name('api.payment.webhook.easypay');
    });
});
