<?php

namespace Sassnowski\LaravelShareableModel;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ShareableLinkServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        Route::bind('shareable_link', function ($value) {
            try {
                return ShareableLink::where('uuid', $value)->firstOrFail();
            } catch(QueryException $e) {
                throw new ModelNotFoundException($e->getMessage());
            }
        });

        $this->publishes([
            __DIR__.'/../config/shareable-model.php' => config_path('shareable-model.php'),
            __DIR__.'/../resources/views/password.blade.php' => resource_path('views/vendor/shareable-model'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shareable-model.php', 'shareable-model');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shareable-model');
    }
}
