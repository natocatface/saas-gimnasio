<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Helpers globales (money(), gym_setting(), etc.)
        require_once app_path('Helpers/helpers.php');
    }

    public function boot(): void
    {
        Paginator::useBootstrapFour();
    }
}
