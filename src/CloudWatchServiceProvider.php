<?php

namespace FelipeChiodini\LaravelCloudWatch;

use Illuminate\Support\ServiceProvider;

class CloudWatchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerClient();
        $this->registerService();
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/cloudwatch.php', 'cloudwatch');
    }

    protected function registerClient()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = new Config($app->make('config')->get('cloudwatch'));
            return new Client($config);
        });
    }

    protected function registerService()
    {
        $this->app->singleton(CloudWatchService::class, function ($app) {
            return new CloudWatchService($app->make(Client::class));
        });
    }
}