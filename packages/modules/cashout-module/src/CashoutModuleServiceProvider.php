<?php

namespace Modules\CashoutModule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CashoutModuleServiceProvider extends ServiceProvider
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
            'prefix'        => 'api/cashout',
            'middleware'    => 'api',
            'namespace'     => 'Modules\CashoutModule\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        });
    }
}
