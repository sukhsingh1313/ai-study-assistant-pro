<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        // Guarantee $errors ViewErrorBag is always defined to prevent null pointer exceptions
        View::composer('*', function ($view) {
            if (!isset($view->getData()['errors'])) {
                $view->with('errors', session('errors', new ViewErrorBag));
            }
        });
    }
}
