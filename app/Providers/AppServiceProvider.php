<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

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
        // =========================
        // FORCE HTTPS
        // =========================
        URL::forceScheme('https');

        // =========================
        // SHARE MENU KE SEMUA VIEW
        // =========================
        View::composer('*', function ($view) {

            $menus = Cache::remember('menus.tree', 60 * 60, function () {
                return \App\Models\Menu::with('childrenRecursive')
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();
            });

            $view->with('menus', $menus);
        });
    }
}
