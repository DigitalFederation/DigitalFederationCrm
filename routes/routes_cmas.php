<?php

/**
 * CMAS (International) Routes
 * --------------------------------------------------------------------------
 * All routes in this file handle INTERNATIONAL licenses and certifications
 * (where is_international = true).
 *
 * Every route lives under:
 *      • prefix  : /cmas
 *      • name    : cmas.*
 *      • permission: access international licenses
 *
 * This namespace is completely separated from national routes to prevent
 * any mixing of national and international content.
 */

use App\Http\Controllers\Cmas\Entity\CertificationAttributedController as CmasEntityCertificationAttributedController;
use App\Http\Controllers\Cmas\Entity\LicenseAttributedController as CmasEntityLicenseAttributedController;
use App\Http\Controllers\Cmas\Entity\LicensePurchaseController as CmasEntityLicensePurchaseController;
use App\Http\Controllers\Cmas\Federation\CertificationAttributedController as CmasFederationCertificationAttributedController;
use App\Http\Controllers\Cmas\Federation\LicenseAttributedController as CmasFederationLicenseAttributedController;
use App\Http\Controllers\Cmas\Individual\CertificationAttributedController as CmasIndividualCertificationAttributedController;
use App\Http\Controllers\Cmas\Individual\CertificationCardController as CmasIndividualCertificationCardController;
use App\Http\Controllers\Cmas\Individual\LicenseAttributedController as CmasIndividualLicenseAttributedController;
use App\Http\Controllers\Cmas\Individual\LicensePurchaseController as CmasIndividualLicensePurchaseController;
use Illuminate\Support\Facades\Route;

/* ------------------------------------------------------------------------ */
/*  Base CMAS group - All international content */
/* ------------------------------------------------------------------------ */

Route::prefix('cmas')
    ->name('cmas.')
    ->middleware(['auth', 'permission:access international licenses'])
    ->group(function () {

        /* -------------------------------------------------------------------- */
        /*  INDIVIDUAL Namespace - International licenses & certifications */
        /* -------------------------------------------------------------------- */

        Route::prefix('individual')
            ->name('individual.')
            ->middleware(['user_group:INDIVIDUAL'])
            ->group(function () {

                // Licenses Attributed (International only)
                Route::controller(CmasIndividualLicenseAttributedController::class)
                    ->prefix('licenses-attributed')
                    ->name('licenses-attributed.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'show')->name('show');
                        Route::delete('/{license_attributed}', 'destroy')->name('delete');
                    });

                // License Purchase (International only)
                Route::controller(CmasIndividualLicensePurchaseController::class)
                    ->prefix('license-purchase')
                    ->name('license-purchase.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'store')->middleware('throttle:10,1')->name('store');
                        Route::get('/success', 'success')->name('success');
                    });

                // Certifications Attributed (International only)
                Route::controller(CmasIndividualCertificationAttributedController::class)
                    ->prefix('certifications')
                    ->name('certifications.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'show')->name('show');
                    });

                // Certification Cards (CMAS - Diving + Scientific)
                Route::controller(CmasIndividualCertificationCardController::class)
                    ->prefix('certification-card')
                    ->name('certification-card.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'show')->name('show');
                        Route::get('/{certificationAttributed}/download', 'download')->name('download');
                    });
            });

        /* -------------------------------------------------------------------- */
        /*  ENTITY Namespace - International licenses & certifications */
        /* -------------------------------------------------------------------- */

        Route::prefix('entity')
            ->name('entity.')
            ->middleware(['user_group:ENTITY'])
            ->group(function () {

                // Licenses Attributed (International only)
                Route::controller(CmasEntityLicenseAttributedController::class)
                    ->prefix('licenses-attributed')
                    ->name('licenses-attributed.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'show')->name('show');
                    });

                // License Purchase (International only)
                Route::controller(CmasEntityLicensePurchaseController::class)
                    ->prefix('license-purchase')
                    ->name('license-purchase.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'store')->middleware('throttle:10,1')->name('store');
                        Route::get('/success', 'success')->name('success');
                    });

                // Certifications Attributed (International only)
                Route::controller(CmasEntityCertificationAttributedController::class)
                    ->prefix('certifications')
                    ->name('certifications.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'show')->name('show');
                    });

                // Member licenses - View licenses attributed to entity's members
                Route::controller(CmasEntityLicenseAttributedController::class)
                    ->prefix('member-licenses')
                    ->name('member-licenses.')
                    ->group(function () {
                        Route::get('/', 'individuals')->name('index');
                    });
            });

        /* -------------------------------------------------------------------- */
        /*  FEDERATION Namespace - International license & certification management */
        /* -------------------------------------------------------------------- */

        Route::prefix('federation')
            ->name('federation.')
            ->middleware(['user_group:FEDERATION,ADMIN'])
            ->group(function () {

                // Licenses Attributed (International only)
                Route::controller(CmasFederationLicenseAttributedController::class)
                    ->prefix('licenses-attributed')
                    ->name('licenses-attributed.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/create/{type}/{committee}', 'create')->name('create');
                        Route::post('/', 'store')->middleware('throttle:6,1')->name('store');
                        Route::get('/{id}', 'show')->name('show');
                        Route::put('/{id}/activate', 'activate')->middleware('throttle:6,1')->name('activate');
                        Route::put('/{id}/cancel', 'cancel')->middleware('throttle:6,1')->name('cancel');
                        Route::put('/{id}/approve', 'approve')->middleware('throttle:6,1')->name('approve');
                        Route::delete('/{id}', 'destroy')->name('delete');
                    });

                // Certifications Attributed (International only)
                Route::controller(CmasFederationCertificationAttributedController::class)
                    ->prefix('certifications-attributed')
                    ->name('certifications-attributed.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'show')->name('show');
                        Route::post('/activate', 'activate')->middleware('throttle:6,1')->name('activate');
                        Route::post('/suspend', 'suspend')->middleware('throttle:6,1')->name('suspend');
                        Route::post('/cancel', 'cancel')->middleware('throttle:6,1')->name('cancel');
                    });
            });
    }); // ── end CMAS group ────────────────────────────────────────────────────
