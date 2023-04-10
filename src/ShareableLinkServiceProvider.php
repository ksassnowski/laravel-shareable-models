<?php

declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ShareableLinkServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->singleton('baseshareablelink', function () {
            return new BaseShareableLink();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/shareable-model.php', 'shareable-model');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shareable-model');
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->registerRouteModelBinding();
        $this->registerMigrations();
        $this->registerPublishing();
    }

    /**
     * Register route binding resolution.
     *
     * @return void
     */
    protected function registerRouteModelBinding()
    {
        Route::bind('shareable_link', function ($value) {
            try {
                return ShareableLink::where('uuid', $value)->firstOrFail();
            } catch (QueryException $e) {
                throw new ModelNotFoundException($e->getMessage());
            }
        });
    }

    /**
     * Register migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && BaseShareableLink::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
                __DIR__.'/../config/shareable-model.php' => config_path('shareable-model.php'),
                __DIR__.'/../resources/views/password.blade.php' => resource_path('views/vendor/shareable-model'),
            ]);
        }
    }
}
