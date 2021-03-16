<?php

namespace Modules\UserModule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class UserModuleServiceProvider extends ServiceProvider
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
            'prefix'        => 'api/user',
            'middleware'    => 'api',
            'namespace'     => 'Modules\UserModule\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        });
    }
}
