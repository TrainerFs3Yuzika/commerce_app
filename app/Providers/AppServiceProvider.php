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

        // Define 'rupiah' directive
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format((float) preg_replace('/[^\d.]/', '', $expression), 0, ',', '.'); ?>";
        });

        // Define 'formatPhone' directive
        Blade::directive('formatPhone', function ($expression) {
            return "<?php echo \\App\\Providers\\formatPhoneNumber($expression); ?>";
        });
        // Define 'percent' directive
        Blade::directive('percent', function ($expression) {
            return "<?php echo intval($expression) . '%'; ?>";
        });
    }
}

// Define the formatPhoneNumber function if it doesn't exist
if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone)
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // Ensure the number starts with '0' and replace it with the Indonesian country code
        if (substr($phone, 0, 1) === '0') {
            $phone = '+62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '+62' . $phone;
        }

        // Format the phone number according to the rules
        return preg_replace('/(\+62)(\d{3})(\d{4})(\d{4})/', '$1 $2-$3-$4', $phone);
    }
}
