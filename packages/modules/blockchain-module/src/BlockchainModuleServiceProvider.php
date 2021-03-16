<?php

namespace Modules\BlockchainModule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BlockchainModuleServiceProvider extends ServiceProvider
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
            'prefix'        => 'api/blockchain',
            'middleware'    => 'api',
            'namespace'     => 'Modules\BlockchainModule\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        });
    }
}
