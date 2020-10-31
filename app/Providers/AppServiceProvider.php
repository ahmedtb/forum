<?php

namespace App\Providers;

use App\Models\Channel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;


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
        schema::defaultstringlength(191);

        view::composer('*',function($view) {
            $channel = cache()->rememberforever('channels',function (){
                return channel::all();
            });
            $view->with('channels', $channel);
        });

        paginator::usebootstrap();


        Validator::extend('spamfree', 'App\Rules\SpamFree@passes');

    }
}
