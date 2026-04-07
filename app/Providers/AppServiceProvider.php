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
            $dbConnection = config('database.default');
            
            // If using SQLite safely ensure the file exists so it doesn't crash
            if ($dbConnection === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if (is_string($dbPath) && $dbPath !== ':memory:' && !file_exists($dbPath)) {
                    touch($dbPath);
                }
            }

            if (! \Illuminate\Support\Facades\Schema::hasTable('migrations')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Ignore database connection errors during initial build steps if database isn't ready
        }
    }
}
