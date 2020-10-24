<?php

namespace App\Providers;

use App\Models\Channel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        //

        if($this->app->isLocal())
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);



        View::composer('*',function($view) {
            $channel = cache()->rememberForever('channels',function (){
                return Channel::all();
            });

            $view->with('channels', $channel);
        });

        Paginator::useBootstrap();

    }
}
