<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SosialMedia;
use Illuminate\Support\Facades\View;

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
        // View Composer for footer social media
        View::composer('frontend.partials.footer', function ($view) {
            $view->with('sosialMedias', SosialMedia::where('is_active', true)->orderBy('id', 'asc')->get());
        });
    }
}
