<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Frontend\CategoryService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

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
    public function boot(Request $request): void
    {
        //
        View::composer('*', function ($view) {
            $categoryService = app(CategoryService::class);
            $categories = $categoryService->getHeaderCategories();

            $view->with('categories', $categories);
        });
        
	    // Force HTTPS for all URLs in production/staging
        if (app()->environment(['production', 'staging', 'local'])) {
            URL::forceScheme('https');

            // This manually tells Laravel the request is secure if it sees the proxy header
            if ($request->header('X-Forwarded-Proto') === 'https') {
                $request->server->set('HTTPS', 'on');
            }
        }
    }
}
