<?php

namespace Modules\MembershipModule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MembershipModuleServiceProvider extends ServiceProvider
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
            'prefix'        => 'api/membership',
            'middleware'    => 'api',
            'namespace'     => 'Modules\MembershipModule\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        });
    }
}
