<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Settings;
use App\Models\Category;
use App\Observers\OrderObserver;
use App\Repositories\PathaoApi\PathaoApiInterface;
use App\Repositories\PathaoApi\PathaoApiRepository;
use App\Repositories\SteadFastApi\SteadFastApiInterface;
use App\Repositories\SteadFastApi\SteadFastApiRepository;
use App\Repositories\RedXApi\RedXApiInterface;
use App\Repositories\RedXApi\RedXApiRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PathaoApiInterface::class,    PathaoApiRepository::class);
        $this->app->bind(SteadFastApiInterface::class,    SteadFastApiRepository::class);
        $this->app->bind(RedXApiInterface::class,    RedXApiRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFour();

        Order::observe(OrderObserver::class);

        // Share Settings globally (cached)
        View::composer('*', function ($view) {
            $settings = optimize('global_settings', function() {
                return Settings::first();
            }, 86400, ['settings']);
            
            $view->with('settings', $settings);
        });

        // Share Categories for frontend views (cached)
        View::composer(['frontend.*', 'frontend.includes.header'], function ($view) {
             $categories = optimize('global_categories_nav', function() {
                return Category::orderBy('title','asc')
                    ->where('status',1)
                    ->with(['subcategories' => function($q) {
                        $q->with('childcategories');
                    }]) 
                    ->get();
            }, 86400, ['categories', 'subcategories', 'childcategories']);
            
            $view->with('categories', $categories);
        });
    }
}
