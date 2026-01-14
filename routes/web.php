<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;

/* ================= Admin Controllers ================= */
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BloodRequestAdminController;
use App\Http\Controllers\Admin\DonorAdminController;
use App\Http\Controllers\Admin\UserAdminController;

/* ================= Recipient Controllers ================= */
use App\Http\Controllers\Recipient\RecipientDashboardController;
use App\Http\Controllers\Recipient\BloodRequestController;

/* ================= Donor Controllers ================= */
use App\Http\Controllers\Donor\DonorDashboardController;
use App\Http\Controllers\Donor\DonorProfileController;
use App\Http\Controllers\Donor\DonorResponseController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified.user'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Common User Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'changePassword'])
        ->name('profile.password');

    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings');

    Route::put('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');

    Route::put('/settings/export', [SettingsController::class, 'export'])
        ->name('settings.export');

    /*
    |--------------------------------------------------------------------------
    | Recipient Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:recipient'])
        ->prefix('recipient')
        ->name('recipient.')
        ->group(function () {

            Route::get('/dashboard', [RecipientDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/statistics', [RecipientDashboardController::class, 'statistics'])
                ->name('statistics');

            Route::resource('blood-requests', BloodRequestController::class)
                ->except(['destroy']);

            Route::post(
                '/blood-requests/{bloodRequest}/cancel',
                [BloodRequestController::class, 'cancel']
            )->name('blood-requests.cancel');

            Route::get(
                '/blood-requests/{bloodRequest}/donors',
                [BloodRequestController::class, 'getMatchingDonors']
            )->name('blood-requests.donors');
        });

    /*
    |--------------------------------------------------------------------------
    | Donor Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:donor'])
        ->prefix('donor')
        ->name('donor.')
        ->group(function () {

            Route::get('/dashboard', [DonorDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/statistics', [DonorDashboardController::class, 'dashboard'])
                ->name('statistics');

            /* ===== Donor Profile ===== */

            Route::get('/profile', [DonorProfileController::class, 'show'])
                ->name('profile.show');

            Route::get('/profile/edit', [DonorProfileController::class, 'edit'])
                ->name('profile.edit');

            Route::put('/profile', [DonorProfileController::class, 'update'])
                ->name('profile.update');

            Route::delete('/profile', [DonorProfileController::class, 'destroy'])
                ->name('profile.destroy');

            Route::post(
                '/profile/create',
                [DonorProfileController::class, 'store']
            )
                ->middleware('donor.profile.missing')
                ->name('profile.store');

            Route::post(
                '/profile/availability',
                [DonorProfileController::class, 'toggleAvailability']
            )->name('availability.toggle');

            /* ===== Blood Requests ===== */

            Route::get(
                '/blood-requests/available',
                [DonorResponseController::class, 'getAvailableRequests']
            )->name('blood-requests.available');

            Route::get(
                '/blood-requests/{bloodRequest}',
                [DonorResponseController::class, 'show']
            )->name('blood-requests.show');

            Route::post(
                '/blood-requests/{bloodRequest}/respond',
                [DonorResponseController::class, 'respond']
            )->name('blood-requests.respond');

            /* ===== Donor Responses ===== */

            Route::get('/responses', [DonorResponseController::class, 'myResponses'])
                ->name('responses.index');

            Route::put(
                '/responses/{donorResponse}/status',
                [DonorResponseController::class, 'updateStatus']
            )->name('responses.update-status');
        });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/statistics', [BloodRequestAdminController::class, 'statistics'])
                ->name('statistics');

            /* ===== Blood Requests ===== */

            Route::get('/blood-requests', [BloodRequestAdminController::class, 'index'])
                ->name('blood-requests.index');

            Route::get(
                '/blood-requests/{bloodRequest}',
                [BloodRequestAdminController::class, 'show']
            )->name('blood-requests.show');

            Route::patch(
                '/blood-requests/{bloodRequest}/approve',
                [BloodRequestAdminController::class, 'approve']
            )->name('blood-requests.approve');

            Route::patch(
                '/blood-requests/{bloodRequest}/fulfill',
                [BloodRequestAdminController::class, 'fulfill']
            )->name('blood-requests.fulfill');

            Route::patch(
                '/blood-requests/{bloodRequest}/cancel',
                [BloodRequestAdminController::class, 'cancel']
            )->name('blood-requests.cancel');

            Route::delete(
                '/blood-requests/{bloodRequest}',
                [BloodRequestAdminController::class, 'destroy']
            )->name('blood-requests.destroy');

            /* ===== Donors ===== */

            Route::get('/donors', [DonorAdminController::class, 'index'])
                ->name('donors.index');

            Route::get('/donors/{donorProfile}', [DonorAdminController::class, 'show'])
                ->name('donors.show');

            Route::patch(
                '/donors/{donorProfile}/approve',
                [DonorAdminController::class, 'approve']
            )->name('donors.approve');

            Route::patch(
                '/donors/{donorProfile}/reject',
                [DonorAdminController::class, 'reject']
            )->name('donors.reject');

            Route::delete(
                '/donors/{donorProfile}',
                [DonorAdminController::class, 'destroy']
            )->name('donors.destroy');

            Route::get(
                '/donors/{donorProfile}/responses',
                [DonorAdminController::class, 'responses']
            )->name('donors.responses');

            Route::get(
                '/donors-statistics',
                [DonorAdminController::class, 'statistics']
            )->name('donors.statistics');

            /* ===== Users ===== */

            Route::get('/users', [UserAdminController::class, 'index'])
                ->name('users.index');

            Route::get('/users/{user}', [UserAdminController::class, 'show'])
                ->name('users.show');

            Route::patch(
                '/users/{user}/verify',
                [UserAdminController::class, 'verify']
            )->name('users.verify');

            Route::patch(
                '/users/{user}/unverify',
                [UserAdminController::class, 'unverify']
            )->name('users.unverify');

            Route::patch(
                '/users/{user}/role',
                [UserAdminController::class, 'updateRole']
            )->name('users.update-role');

            Route::delete(
                '/users/{user}',
                [UserAdminController::class, 'destroy']
            )->name('users.destroy');

            /* ===== Reports ===== */

            Route::get('/reports', [AdminDashboardController::class, 'reports'])
                ->name('reports');

            Route::get(
                '/reports/donations',
                [AdminDashboardController::class, 'donationReport']
            )->name('reports.donations');

            Route::get(
                '/reports/requests',
                [AdminDashboardController::class, 'requestReport']
            )->name('reports.requests');
        });
});

/*
|--------------------------------------------------------------------------
| Public Pages (Guest)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::view('/about', 'public.about')->name('about');
    Route::view('/how-it-works', 'public.how-it-works')->name('how-it-works');
    Route::view('/eligibility', 'public.eligibility')->name('eligibility');
    Route::view('/contact', 'public.contact')->name('contact');
    Route::view('/faq', 'public.faq')->name('faq');
    Route::view('/terms', 'public.terms')->name('terms');
    Route::view('/privacy', 'public.privacy')->name('privacy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
