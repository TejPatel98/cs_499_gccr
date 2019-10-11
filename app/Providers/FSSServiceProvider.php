<?php

namespace App\Providers;

use App\Custom\FishbackStockScanner\CommandLineExecutor;
use Illuminate\Support\ServiceProvider;

class FSSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->bind('fsscle', function(){

			return new CommandLineExecutor();
		});
    }
}
