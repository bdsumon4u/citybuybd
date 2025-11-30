<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Repositories\PathaoApi\PathaoApiInterface;
use App\Repositories\PathaoApi\PathaoApiRepository;
use App\Repositories\SteadFastApi\SteadFastApiInterface;
use App\Repositories\SteadFastApi\SteadFastApiRepository;
use App\Repositories\RedXApi\RedXApiInterface;
use App\Repositories\RedXApi\RedXApiRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


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
    }
}
