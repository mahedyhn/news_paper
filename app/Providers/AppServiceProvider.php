<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
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
        // Share categories with all views
        View::composer('frontend.includes.header', function ($view) {
            $categories = Category::all();
            $view->with('categories', $categories);
        });

        // Also share with the master layout and all views that might need it
        View::composer('frontend.*', function ($view) {
            if (!$view->offsetExists('categories')) {
                $categories = Category::all();
                $view->with('categories', $categories);
            }
        });
    }
}
