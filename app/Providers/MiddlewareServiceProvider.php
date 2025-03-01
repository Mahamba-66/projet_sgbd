<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('admin', function ($app) {
            return new AdminMiddleware();
        });
    }

    public function boot()
    {
        //
    }
}
