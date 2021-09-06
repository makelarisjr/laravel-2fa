<?php

namespace MakelarisJR\Laravel2FA;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MakelarisJR\Laravel2FA\Http\Middleware\OTPMiddleware;
use MakelarisJR\Laravel2FA\Services\Laravel2FA;

class Laravel2FAServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('laravel2fa', fn ($app) => new Laravel2FA);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel2fa.php' => config_path('laravel2fa.php'),
            ], 'config');
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel2fa');

        if (config('laravel2fa.enabled')) {
            $this->registerRoutes();
            $this->registerMiddleware();
        }
    }

    public function registerRoutes(): void
    {
        if (! config('laravel2fa.register_routes')) {
            return;
        }
        Route::group(['prefix' => config('laravel2fa.routes_prefix'), 'middleware' => ['web', 'auth']], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    public function registerMiddleware(): void
    {
        if (! config('laravel2fa.register_middleware')) {
            return;
        }
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('otp', OTPMiddleware::class);
    }
}
