<?php

namespace Sassnowski\LaravelShareableModel;

use Hashids\Hashids;
use Hashids\HashidsInterface;
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
            /** @var HashidsInterface $hashids */
            $hashids = app()->make(HashidsInterface::class);

            $uuid = $hashids->decodeHex($value);

            return ShareableLink::where('uuid', $uuid)->firstOrFail();
        });

        $this->publishes([
            __DIR__.'/../config/shareable-model.php' => config_path('shareable-model.php'),
            __DIR__.'/../resources/views/password.blade.php' => resource_path('views/vendor/shareable-model'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        app()->bind(HashidsInterface::class, function () {
            return new Hashids(
                config('shareable-model.hashids.salt'),
                config('shareable-model.hashids.min_hash_length'),
                config('shareable-model.hashids.alphabet')
            );
        });

        $this->mergeConfigFrom(__DIR__.'/../config/shareable-model.php', 'shareable-model');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shareable-model');
    }
}
