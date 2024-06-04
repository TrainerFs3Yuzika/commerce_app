<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        Paginator::useBootstrapFive();
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp. ' . number_format((float) preg_replace('/[^\d.]/', '', $expression), 0, ',', '.'); ?>";
        });
    }
}
