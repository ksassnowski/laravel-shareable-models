<?php

namespace Sassnowski\LaravelShareableModel\Tests;

use Illuminate\Routing\Router;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Sassnowski\LaravelShareableModel\ShareableLinkServiceProvider;
use Sassnowski\LaravelShareableModel\Http\Middleware\ValidateShareableLink;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpMiddleware();

        $this->setUpRoutes();

        $this->setUpDatabase();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ShareableLinkServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => __DIR__.'/temp/database.sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    private function setUpMiddleware()
    {
        $this->app[Router::class]->aliasMiddleware('shared', ValidateShareableLink::class);
    }

    private function setUpDatabase()
    {
        file_put_contents(__DIR__.'/temp/database.sqlite', null);

        $this->app['db']->connection()->getSchemaBuilder()->create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->timestamps();
        });

        $this->artisan('migrate');
    }

    private function setUpRoutes()
    {
        // Since these routes don't get loaded as part of the regular application bootstrapping
        // we have to explicitly apply the `bindings` middleware here.
        \Route::get('shared/{shareable_link}', ['middleware' => ['bindings', 'shared'], function ($shareableLink) {
            return $shareableLink->shareable->path;
        }]);
    }
}
