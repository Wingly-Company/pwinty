<?php

namespace Wingly\Pwinty;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Wingly\Pwinty\Commands\SignWebhookURL;

class PwintyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Route::group([
            'prefix' => 'pwinty',
            'namespace' => 'Wingly\Pwinty\Http\Controllers',
            'as' => 'pwinty.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pwinty.php', 'pwinty');

        $this->app->singleton(Pwinty::class, function ($app) {
            $client = app(Client::class);

            return (new Pwinty($client))
                ->setApiKey(config('pwinty.apiKey'))
                ->setMerchantId(config('pwinty.merchantId'))
                ->setApiUrl(config('pwinty.api'));
        });
    }

    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/pwinty.php' => config_path('pwinty.php'),
        ], 'pwinty.config');

        $this->commands([
            SignWebhookURL::class
        ]);
    }
}
