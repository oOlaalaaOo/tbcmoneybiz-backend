<?php

namespace Modules\PlanModule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PlanModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        Route::group([
            'prefix'        => 'api/plan',
            'middleware'    => 'api',
            'namespace'     => 'Modules\PlanModule\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        });
    }
}
