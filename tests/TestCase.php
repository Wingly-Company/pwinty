<?php

namespace Wingly\Pwinty\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wingly\Pwinty\PwintyServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [PwintyServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('pwinty.apiKey', getenv('PWINTY_API_KEY'));

        $app['config']->set('pwinty.merchantId', getenv('PWINTY_MERCHANT_ID'));

        $app['config']->set('pwinty.api', 'sandbox');
    }
}
