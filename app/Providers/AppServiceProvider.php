<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\CartService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
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
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            $categoryService = app(CategoryService::class);
            $categories = $categoryService->getHeaderCategories();
            $cartService = app(CartService::class);
            $cartCount = $cartService->getCartCount();
            
            $view->with([
                'categories' => $categories,
                'cartCount' => $cartCount,
            ]);
        });
        
	    // Force HTTPS for all URLs in production/staging
        if (app()->environment(['production', 'staging', 'local'])) {
            URL::forceScheme('https');

            // This manually tells Laravel the request is secure if it sees the proxy header
            if ($request->header('X-Forwarded-Proto') === 'https') {
                $request->server->set('HTTPS', 'on');
            }
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            function (): HtmlString {
                if (!request()->routeIs(
                    'filament.admin.resources.orders.invoice'
                )) {
                    return new HtmlString("");
                }

                return new HtmlString(<<<'HTML'
                    <style>
                        @media print {
                            @page {
                                size: A4 portrait;
                                margin: 10mm;
                            }

                            .fi-breadcrumbs,
                            .fi-page-header-actions,
                            .invoice-print-action {
                                display: none !important;
                            }

                            .fi-page {
                                padding: 0 !important;
                                max-width: 100% !important;
                            }

                            .fi-section {
                                box-shadow: none !important;
                                margin-bottom: 6px !important;
                                break-inside: avoid;
                                page-break-inside: avoid;
                            }

                            .fi-section-header {
                                padding: 6px 10px !important;
                            }

                            .fi-section-content-ctn {
                                padding: 8px 10px !important;
                            }
                        }
                    </style>
                    HTML
                );
            },
        );
    }
}
