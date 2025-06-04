<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

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
        if (!app()->environment('local')) {
            URL::forceScheme('https');
        }

        // Add formatRupiah helper function for Blade templates
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp. ' . number_format({$expression}, 0, ',', '.'); ?>";
        });
    }
}