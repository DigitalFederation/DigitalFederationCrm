<?php

use App\Http\Controllers\ApplicationDocumentController;
use App\Http\Controllers\DownloadMediaController;
use App\Http\Controllers\Entity\AthleteController as EntityAthleteController;
use App\Http\Controllers\Entity\CertificationAttributedController as EntityCertificationAttributedController;
use App\Http\Controllers\Entity\CoachController as EntityCoachController;
use App\Http\Controllers\Entity\DashboardController as EntityDashboardController;
use App\Http\Controllers\Entity\DivingCourseController as EntityDivingCourseController;
use App\Http\Controllers\Entity\DivingInstructorController as EntityDivingInstructorController;
use App\Http\Controllers\Entity\DivingLicensePdfController;
use App\Http\Controllers\Entity\DivingLicensesController;
use App\Http\Controllers\Entity\DivingLocationController as EntityDivingLocationController;
use App\Http\Controllers\Entity\DivingLogValidationController as EntityDivingLogValidationController;
use App\Http\Controllers\Entity\DivingProfessionalsController as EntityDivingProfessionalsController;
use App\Http\Controllers\Entity\DocumentController as EntityDocumentController;
use App\Http\Controllers\Entity\DocumentPaymentController as EntityDocumentPaymentController;
use App\Http\Controllers\Entity\EntityApplicationController;
use App\Http\Controllers\Entity\EntityController as CurrentEntityController;
use App\Http\Controllers\Entity\IndividualApproveController;
use App\Http\Controllers\Entity\IndividualController as EntityIndividualController;
use App\Http\Controllers\Entity\IndividualInsuranceController;
use App\Http\Controllers\Entity\IndividualLicenseAttributedController;
use App\Http\Controllers\Entity\InstructorApproveController;
use App\Http\Controllers\Entity\InternationalDivingInstructorController as EntityInternationalDivingInstructorController;
use App\Http\Controllers\Entity\InternationalLicenseAttributedController as EntityInternationalLicenseAttributedController;
use App\Http\Controllers\Entity\InternationalLicensePurchaseController as EntityInternationalLicensePurchaseController;
use App\Http\Controllers\Entity\LicenseAttributedController as EntityLicensesAttributedController;
use App\Http\Controllers\Entity\LicensePurchaseController as EntityLicensePurchaseController;
use App\Http\Controllers\Entity\OfficialDocumentsController as EntityOfficialDocumentsController;
use App\Http\Controllers\Entity\PublicPageController as EntityPublicPageController;
use App\Http\Controllers\Entity\ScientificInstructorController as EntityScientificInstructorController;
use App\Http\Controllers\Entity\SeparatedLicenseAttributedController as EntitySeparatedLicenseAttributedController;
use App\Http\Controllers\Shared\InsuranceDocumentController;
use Illuminate\Support\Facades\Route;

// Entity
Route::group(['prefix' => 'entity', 'middleware' => ['user_group:ENTITY']], function () {

    Route::controller(EntityDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('entity.dashboard');
    });

    // Profile
    Route::get('/profile/edit', [CurrentEntityController::class, 'edit'])->name('entity.profile.edit');
    Route::name('entity.')->group(
        function () {
            Route::resource('/profile', CurrentEntityController::class)->only(['update']);
        }
    );

    // Public Page Management
    Route::get('/public-page', [EntityPublicPageController::class, 'index'])->name('entity.public-page.index');

    Route::controller(DownloadMediaController::class)->group(function () {
        Route::post('/download', 'download')->name('entity.media.download');
        Route::post('/delete', 'delete')->name('entity.media.delete');
    });

    Route::controller(EntityDocumentController::class)->group(function () {
        Route::get('/documents', 'index')->name('entity.document.index');
        Route::get('/document/download/{id}', 'download')->name('entity.document.download');
        Route::get('/document/{id}/moloni-pdf', \App\Http\Controllers\Shared\MoloniDocumentPdfController::class)->name('entity.document.moloni-pdf');
        Route::get('/document/{id}', 'show')->name('entity.document.show');
    });
    Route::post('/document/{id}/pay', [EntityDocumentPaymentController::class, 'store'])->name('entity.document.pay');

    Route::controller(EntityOfficialDocumentsController::class)->group(function () {
        Route::get('/official-documents', 'index')->name('entity.official-documents.index');
        Route::get('/official-documents/download/{document}', 'download')->name('entity.official-documents.download');
        Route::delete('/official-documents/{document}', 'destroy')->name('entity.official-documents.delete');
    });

    // Federation / Organizations routes
    Route::controller(\App\Http\Controllers\Entity\FederationController::class)->group(function () {
        Route::get('/federations', 'index')->name('entity.federation.index');
        Route::get('/federation/{id}', 'show')->name('entity.federation.show');
    });

    Route::controller(EntityLicensesAttributedController::class)->group(function () {
        Route::get('/licenses-attributed', 'index')->name('entity.license-attributed.index');
    });

    // Separated licenses-attributed routes by committee (to avoid mixing entity types)
    Route::controller(EntitySeparatedLicenseAttributedController::class)->group(function () {
        // Entity licenses
        Route::get('/sport-licenses-attributed', 'sportEntity')->name('entity.sport-licenses-attributed.index');
        Route::get('/international-diving-licenses-attributed', 'divingInternationalEntity')->name('entity.international-diving-licenses-attributed.index');
        Route::get('/scientific-licenses-attributed', 'scientificEntity')->name('entity.scientific-licenses-attributed.index');

        // Member licenses
        Route::get('/sport-member-licenses-attributed', 'sportMembers')->name('entity.sport-member-licenses-attributed.index');
        Route::get('/international-diving-member-licenses-attributed', 'divingInternationalMembers')->name('entity.international-diving-member-licenses-attributed.index');
        Route::get('/scientific-member-licenses-attributed', 'scientificMembers')->name('entity.scientific-member-licenses-attributed.index');
        Route::get('/national-diving-member-licenses-attributed', 'nationalDivingMembers')->name('entity.national-diving-member-licenses-attributed.index');
    });

    // Redirect old generic route to sport license purchase (for browser history compatibility)
    Route::get('/license-purchase', function () {
        return redirect()->route('entity.sport-license-purchase.index');
    })->name('entity.license-purchase.index');

    Route::controller(EntityLicensePurchaseController::class)->group(function () {
        Route::post('/license-purchase', 'store')->name('entity.license-purchase.store');
        Route::get('/license-purchase/success', 'success')->name('entity.license-purchase.success');

        // Separated license purchase routes by committee
        // Entity licenses
        Route::get('/sport-license-purchase', 'sportEntity')->name('entity.sport-license-purchase.index');
        Route::get('/international-diving-license-purchase', 'divingInternationalEntity')->name('entity.international-diving-license-purchase.index');
        Route::get('/scientific-license-purchase', 'scientificEntity')->name('entity.scientific-license-purchase.index');

        // Member licenses
        Route::get('/sport-member-license-purchase', 'sportMembers')->name('entity.sport-member-license-purchase.index');
        Route::get('/international-diving-member-license-purchase', 'divingInternationalMembers')->name('entity.international-diving-member-license-purchase.index');
        Route::get('/scientific-member-license-purchase', 'scientificMembers')->name('entity.scientific-member-license-purchase.index');
        Route::get('/national-diving-member-license-purchase', 'nationalDivingMembers')->name('entity.national-diving-member-license-purchase.index');
    });

    // International License Routes
    Route::controller(EntityInternationalLicensePurchaseController::class)->group(function () {
        Route::get('/international-license-purchase', 'index')->name('entity.international-license-purchase.index');
        Route::post('/international-license-purchase', 'store')->name('entity.international-license-purchase.store');
        Route::get('/international-license-purchase/success', 'success')->name('entity.international-license-purchase.success');
    });

    Route::controller(EntityInternationalLicenseAttributedController::class)->group(function () {
        Route::get('/international-licenses-attributed', 'index')->name('entity.international-licenses-attributed.index');
        Route::get('/international-licenses-attributed/individuals', 'individuals')->name('entity.international-licenses-attributed.individuals');
    });

    // Memberships entity.insurances.index entity.subscriptions.index
    Route::controller(\App\Http\Controllers\Entity\MemberInsuranceController::class)->group(function () {
        Route::get('/insurances', 'index')->name('entity.insurances.index');
    });

    Route::controller(\App\Http\Controllers\Entity\MemberSubscriptionController::class)->group(function () {
        Route::get('/subscriptions', 'index')->name('entity.subscriptions.index');
        Route::post('/subscriptions', 'store')->name('entity.subscriptions.store');
        Route::get('/subscriptions/{subscription}', 'show')->name('entity.subscriptions.show');
        Route::put('/subscriptions/{subscription}', 'update')->name('entity.subscriptions.update');
        Route::delete('/subscriptions/{subscription}', 'destroy')->name('entity.subscriptions.delete');
        // Route for subscribing to membership packages (including insurance-only)
        Route::post('/membership-packages/{package}/subscribe', 'store')->name('entity.membership-packages.subscribe');

        // Member subscription routes (alias for consistency)
        Route::get('/member-subscriptions/{subscription}', 'show')->name('entity.member-subscriptions.show');
        Route::post('/member-subscriptions/{subscription}/renew', 'renew')->name('entity.member-subscriptions.renew');
    });

    /**
     * Memberships and insurances
     */
    Route::prefix('individual-insurances')->name('entity.individual-insurances.')->group(function () {
        Route::get('/', [IndividualInsuranceController::class, 'index'])
            ->name('index');
        Route::post('/individual-insurances/assign', [IndividualInsuranceController::class, 'store'])
            ->name('assign');
    });

    // Insurance Documents
    Route::prefix('insurances/{insurance}/document')->name('entity.insurances.document.')->group(function () {
        Route::get('/', [InsuranceDocumentController::class, 'show'])->name('show');
        Route::get('/download', [InsuranceDocumentController::class, 'download'])->name('download');
        Route::get('/conditions', [InsuranceDocumentController::class, 'downloadConditions'])->name('conditions');
    });

    // Individual Subscription
    Route::name('entity.')->group(function () {
        Route::resource('individual-subscriptions', App\Http\Controllers\Entity\IndividualSubscriptionController::class)
            ->only(['index', 'show']);
    });

    // Individual Membership Management by Entity
    Route::prefix('individual-memberships')->name('entity.individual-memberships.')->group(function () {
        Route::get('/', [App\Http\Controllers\Entity\EntityIndividualSubscriptionController::class, 'index'])
            ->name('index');
        Route::get('/preview/{package}', [App\Http\Controllers\Entity\EntityIndividualSubscriptionController::class, 'preview'])
            ->name('preview');
        Route::post('/process/{package}', [App\Http\Controllers\Entity\EntityIndividualSubscriptionController::class, 'process'])
            ->name('process');
        Route::get('/history', [App\Http\Controllers\Entity\EntityIndividualSubscriptionController::class, 'history'])
            ->name('history');
        Route::get('/{subscription}', [App\Http\Controllers\Entity\EntityIndividualSubscriptionController::class, 'show'])
            ->name('show');
    });

    Route::prefix('licenses-attributed')->name('entity.licenses-attributed.')->group(function () {
        Route::resource('/individuals', IndividualLicenseAttributedController::class)->only(['index']);
    });

    Route::middleware([
        'check.entity.role',
    ])->group(function () {
        Route::controller(EntityCertificationAttributedController::class)->group(function () {
            Route::get('/certifications', 'index')->name('entity.certifications.index');
            Route::get('/certifications-attributed', 'index')->name('entity.certification-attributed.index');
            Route::get('/certification-attributed/create', 'create')->name('entity.certification-attributed.create');
            Route::get('/certification-attributed/wizard/create', 'createWizard')->name('entity.certification-attributed.wizard.create');
            Route::post('/certification-attributed', 'store')->name('entity.certification-attributed.store');
            Route::get('/certification-attributed/{certification}', 'show')->name('entity.certification-attributed.show');
            Route::put('/certification-attributed/{license_attributed}', 'update')->name('entity.certification-attributed.update');
            Route::delete('/certification-attributed/{license_attributed}', 'destroy')->name('entity.certification-attributed.delete');
        });
    });

    Route::controller(EntityIndividualController::class)->group(function () {
        Route::get('individuals', 'index')->name('entity.individual.index');
        Route::get('/individuals/create', 'create')->name('entity.individual.create');
        Route::get('/individual/{id}', 'show')->name('entity.individual.show');
        Route::get('/individual/{individual}/files', 'files')->name('entity.individual.files');
        Route::get('/individual/{id}/edit', 'edit')->name('entity.individual.edit');
        Route::put('/individual/{id}', 'update')->name('entity.individual.update');
        Route::post('/individuals', 'store')->name('entity.individual.store');

        Route::delete('/individual/{id}', 'destroy')->name('entity.individual.delete');
    });

    Route::controller(IndividualApproveController::class)->group(function () {
        Route::get('/individual-approve', 'index')->name('entity.individual-approve.index');
        Route::post('/individual-approve', 'store')->name('entity.individual-approve.store');
    });

    Route::controller(EntityDivingProfessionalsController::class)->group(function () {
        Route::get('diving-professionals', 'index')->name('entity.diving_professionals.index');
        Route::delete('diving-professionals/cancel-invitation/{invitation}', 'cancelInvitation')->name('entity.diving_professionals.cancel_invitation');
        Route::delete('diving-professionals/remove/{id}', 'remove')->name('entity.diving_professionals.remove');
    });

    Route::controller(EntityDivingInstructorController::class)->middleware('check_entity_can_invite:diving')->group(function () {
        Route::get('diving-instructors', 'index')->name('entity.diving-instructor.index');
        Route::delete('/diving-instructors/{id}', 'destroy')->name('entity.diving-instructor.delete');
    });

    Route::controller(EntityScientificInstructorController::class)->middleware('check_entity_can_invite:scientific')->group(function () {
        Route::get('scientific-instructors/', 'index')->name('entity.scientific-instructor.index');
        Route::delete('scientific-instructors/cancel-invitation/{invitation}', 'cancelInvitation')->name('entity.scientific-instructor.cancel_invitation');
        Route::delete('scientific-instructors/remove/{id}', 'remove')->name('entity.scientific-instructor.remove');
    });

    Route::controller(EntityInternationalDivingInstructorController::class)->middleware('check_entity_can_invite:diving')->group(function () {
        Route::get('international-diving-instructors/', 'index')->name('entity.international-diving-instructor.index');
        Route::delete('international-diving-instructors/cancel-invitation/{invitation}', 'cancelInvitation')->name('entity.international-diving-instructor.cancel_invitation');
        Route::delete('international-diving-instructors/remove/{id}', 'remove')->name('entity.international-diving-instructor.remove');
    });

    Route::controller(InstructorApproveController::class)->group(function () {
        Route::get('/intructor-approve/{association_id}/{answer}', 'update')->name('entity.instructor.update');
    });

    Route::controller(EntityCoachController::class)->middleware('check_entity_can_invite:sport')->group(function () {
        Route::get('coaches', 'index')->name('entity.coach.index');
        Route::delete('/coaches/{id}', 'destroy')->name('entity.coach.delete');
        Route::delete('/coaches/invitation/{id}', 'cancelInvitation')->name('entity.coach.cancel-invitation');
    });

    Route::controller(EntityAthleteController::class)->group(function () {
        Route::get('athletes/', 'index')->name('entity.athlete.index');
        Route::delete('/athletes/{id}', 'destroy')->name('entity.athlete.delete');
    });

    Route::controller(EntityDivingLogValidationController::class)->group(function () {
        Route::get('/diving-log-validation', 'index')->name('entity.diving-log-validation.index');
        Route::get('/diving-log-validation/approved', 'approvedDives')->name('entity.diving-log-validation.approved');
        Route::put('/diving-log-validation/{id}', 'update')->name('entity.diving-log-validation.update');
        Route::get('/diving-log-validation/{id}', 'show')->name('entity.diving-log-validation.show');
    });

    Route::controller(EntityDivingLocationController::class)->group(function () {
        Route::get('/diving-location', 'index')->name('entity.diving-location.index');
        Route::get('/diving-location/create', 'create')->name('entity.diving-location.create');
        Route::post('/diving-location', 'store')->name('entity.diving-location.store');
        Route::get('/diving-location/{id}', 'show')->name('entity.diving-location.show');
        Route::get('/diving-location/{id}/edit', 'edit')->name('entity.diving-location.edit');
        Route::put('/diving-location/{id}', 'update')->name('entity.diving-location.update');
        Route::delete('/diving-location/{id}', 'destroy')->name('entity.diving-location.delete');
    });

    // Diving Courses Management
    Route::get('diving-courses', [EntityDivingCourseController::class, 'index'])->name('entity.diving-courses.index');

    Route::prefix('evt-events')->name('entity.evt-events.')->group(function () {
        // Default route redirects to competitions (sports events are primary)
        Route::redirect('/', 'evt-events/competitions');

        Route::get('/competitions', [\App\Http\Controllers\Entity\EvtEvents\EventsController::class, 'competitionsIndex'])->name('competitions.index');
        Route::get('/organization', [\App\Http\Controllers\Entity\EvtEvents\EventsController::class, 'index'])->name('events.index');
        Route::get('/events/{event}', [\App\Http\Controllers\Entity\EvtEvents\EventsController::class, 'show'])->name('events.show');

        Route::prefix('events/{event}')->name('events.')->group(function () {

            Route::resource('download-media', \App\Http\Controllers\Common\DownloadEventMediaController::class)->only(['store']);
            Route::resource('individual-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\IndividualEnrollmentController::class)->only(['index', 'create']);
            Route::get('individual-enrollment/export', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\IndividualEnrollmentController::class, 'export'])
                ->name('individual-enrollment.export');
            Route::resource('athlete-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\AthleteEnrollmentController::class)->only(['index', 'create', 'store']);
            Route::get('athlete-enrollment/public', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\AthleteEnrollmentController::class, 'publicIndex'])
                ->name('athlete-enrollment.public');
            Route::delete('athlete-enrollment/{athleteEnrollment}', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\AthleteEnrollmentController::class, 'destroy'])
                ->name('athlete-enrollment.destroy');

            Route::resource('staff-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\StaffEnrollmentController::class)->only(['index', 'create', 'store']);
            Route::resource('coach-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\CoachEnrollmentController::class)
                ->only(['index', 'create', 'store', 'destroy']);

            Route::resource('officials-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\TeamOfficialEnrollmentController::class)
                ->only(['index', 'create', 'store', 'destroy']);

            Route::get('/organizer-enrollments/{enrollmentType}', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\OrganizerEnrollmentsController::class, 'index'])
                ->name('organizer-enrollments.index');
            Route::post('/organizer-enrollments/{enrollmentType}/export', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\OrganizerEnrollmentsController::class, 'export'])
                ->name('organizer-enrollments.export');

            Route::get('/enrollment/{type}', [\App\Http\Controllers\Entity\EvtEvents\EnrollmentsController::class, 'create'])
                ->name('enrollments.create');

            Route::get('/registration/', [\App\Http\Controllers\Entity\EvtEvents\RegistrationController::class, 'create'])
                ->name('enrollments.pre-register');

            // Step 2: Review & Pay
            Route::get('/review/', [\App\Http\Controllers\Entity\EvtEvents\ReviewController::class, 'show'])
                ->name('review');

            // Step 3: Confirmed Enrollments
            Route::get('/confirmed-enrollments/', [\App\Http\Controllers\Entity\EvtEvents\ConfirmedEnrollmentsController::class, 'show'])
                ->name('confirmed-enrollments');

            Route::prefix('disciplines/{discipline}')->name('disciplines.')->group(function () {
                Route::resource('athlete-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\AthleteEnrollmentController::class)->only(['index', 'create', 'store']);
                Route::resource('staff-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\StaffEnrollmentController::class)->only(['index', 'create', 'store']);
                Route::resource('coach-enrollment', \App\Http\Controllers\Entity\EvtEvents\Enrollments\CoachEnrollmentController::class)->only(['index', 'create', 'store']);
                Route::resource('enrollment', \App\Http\Controllers\Entity\EvtEvents\EnrollmentsController::class)->only(['index', 'create', 'store']);
            });

            // Enrolled Lists
            Route::get('/overview/athletes', [\App\Http\Controllers\Entity\EvtEvents\EventsController::class, 'athletesOverview'])
                ->name('overview.athletes');

            // Waiting list
            // Route::get('waiting-list', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\WaitingListController::class, 'index'])->name('waiting-list.index');
            // Route::post('waiting-list', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\WaitingListController::class, 'store'])->name('waiting-list.store');
            // Route::delete('waiting-list/{enrollmentType}/{id}', [\App\Http\Controllers\Entity\EvtEvents\Enrollments\WaitingListController::class, 'destroy'])->name('waiting-list.destroy');

        });

        Route::prefix('competition/{competition}')->name('competitions.')->group(function () {
            Route::resource('disciplines', \App\Http\Controllers\Entity\EvtEvents\DisciplineController::class)->only('index');
        });
    });

    Route::prefix('attachments')->name('entity.')->group(function () {
        Route::prefix('committee/{committee?}')->name('committee.')->group(function () {
            Route::resource('attachments', \App\Http\Controllers\Entity\AttachmentsController::class)
                ->except(['show', 'edit', 'update', 'create']);
        });

        // Routes for no committees (null committee)
        Route::resource('attachments', \App\Http\Controllers\Entity\AttachmentsController::class)
            ->parameters(['attachments' => 'attachment'])
            ->except(['show', 'edit', 'update', 'create']);

        Route::post('/attachments/download/{id}', [\App\Http\Controllers\Entity\AttachmentsController::class, 'download'])
            ->name('attachments.download');
    });

    // Diving Licenses
    Route::prefix('diving-licenses')->name('entity.diving_licenses.')->group(function () {
        Route::get('/', [DivingLicensesController::class, 'index'])->name('index');
        Route::get('/create', [DivingLicensesController::class, 'create'])->name('create');
        Route::post('/', [DivingLicensesController::class, 'store'])->name('store');
        Route::get('/request', [DivingLicensesController::class, 'requestLicense'])->name('request');
        Route::post('/submit', [DivingLicensesController::class, 'submitLicenseRequest'])->name('submit');
        Route::get('/invitations', [DivingLicensesController::class, 'showInvitations'])->name('invitations');
        Route::delete('/invitations/{invitation}', [DivingLicensesController::class, 'cancelInvitation'])->name('cancel_invitation');
        Route::get('/{licenseAttributed}/pdf', [DivingLicensePdfController::class, 'show'])
            ->name('pdf')
            ->middleware('throttle:10,1');
        Route::get('/{licenseAttributed}', [DivingLicensesController::class, 'show'])->name('show');
        Route::get('/{licenseAttributed}/invite-director', [DivingLicensesController::class, 'inviteDirector'])->name('invite_director');
        Route::post('/{licenseAttributed}/send-director-invitation', [DivingLicensesController::class, 'sendDirectorInvitation'])->name('send_director_invitation');
    });

    // Diving License Directors Management
    Route::prefix('diving-licenses/{licenseAttributed}/directors')->name('entity.diving_license_directors.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Entity\DivingLicenseDirectorsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Entity\DivingLicenseDirectorsController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Entity\DivingLicenseDirectorsController::class, 'store'])->name('store');
        Route::delete('/{invitation}/remove', [\App\Http\Controllers\Entity\DivingLicenseDirectorsController::class, 'remove'])->name('remove');
        Route::delete('/{invitation}/cancel', [\App\Http\Controllers\Entity\DivingLicenseDirectorsController::class, 'cancelInvitation'])->name('cancel_invitation');
    });

    // Event Applications
    Route::get('event-applications/available-templates', [EntityApplicationController::class, 'availableTemplates'])
        ->name('entity.event-applications.available-templates');
    Route::get('event-applications/create/template/{template}', [EntityApplicationController::class, 'createFromTemplate'])
        ->name('entity.event-applications.create-from-template');
    Route::get('event-applications/create/direct', [EntityApplicationController::class, 'createDirect'])
        ->name('entity.event-applications.create-direct');
    Route::post('event-applications/{application}/submit', [EntityApplicationController::class, 'submit'])
        ->name('entity.event-applications.submit');
    Route::get('event-applications/{application}/pdf', [EntityApplicationController::class, 'exportPdf'])
        ->name('entity.event-applications.pdf');
    Route::resource('event-applications', EntityApplicationController::class)
        ->names('entity.event-applications')
        ->parameters(['event-applications' => 'application']);

    // Application Documents (Common routes accessible to Entity)
    Route::post('application-documents/upload', [ApplicationDocumentController::class, 'upload'])
        ->name('entity.application-documents.upload');
    Route::get('application-documents/{document}/download', [ApplicationDocumentController::class, 'download'])
        ->name('entity.application-documents.download');
    Route::delete('application-documents/{document}', [ApplicationDocumentController::class, 'destroy'])
        ->name('entity.application-documents.destroy');
});
