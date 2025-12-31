<?php

namespace App\Providers;

use App\Models\BloodRequest;
use App\Observers\BloodRequestObserver;
use App\Models\DonorProfile;
use App\Observers\DonorProfileObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BloodRequest::observe(BloodRequestObserver::class);
        DonorProfile::observe(DonorProfileObserver::class);
    }
}
