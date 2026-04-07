<?php

namespace App\Providers;

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
        // Auto-run migrations for Laravel Cloud or local environments if not already migrated 
        // to prevent 500 server errors on the first load due to missing sessions/users tables.
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('migrations')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Ignore database connection errors during initial build steps if database isn't ready
        }
    }
}
