<?php

namespace Modules\AuthModule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthModuleServiceProvider extends ServiceProvider
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
        Route::group([
            'prefix'        => 'api/auth',
            'middleware'    => 'api',
            'namespace'     => 'Modules\AuthModule\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        });
    }
}
