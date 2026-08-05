<?php

use Illuminate\Foundation\Application;

if (method_exists(Application::class, 'configure')) {
    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__.'/../routes/web.php',
            commands: __DIR__.'/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function ($middleware) {
            $middleware->alias([
                'auth' => \App\Http\Middleware\Authenticate::class,
                'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            ]);
        })
        ->withExceptions(function ($exceptions) {
            //
        })->create();
}

/*
|--------------------------------------------------------------------------
| Legacy Application Initialization (Laravel 9/10 Compatible)
|--------------------------------------------------------------------------
*/
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
