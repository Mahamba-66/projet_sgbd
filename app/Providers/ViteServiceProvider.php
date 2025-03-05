<?php

namespace App\Providers;

use App\Support\CustomVite;
use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('vite', function () {
            return new CustomVite();
        });
    }

    public function boot()
    {
        //
    }
}
