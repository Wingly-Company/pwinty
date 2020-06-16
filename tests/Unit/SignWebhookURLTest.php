<?php

namespace Wingly\Pwinty\Tests\Unit;

use Illuminate\Support\Facades\URL;
use Wingly\Pwinty\Tests\TestCase;

class SignWebhookURLTest extends TestCase
{
    public function test_it_generates_a_signed_url()
    {
        $url = URL::signedRoute('pwinty.webhook');

        $this->artisan('pwinty:sign')
            ->expectsOutput("Grab your URL: \"{$url}\"")
            ->assertExitCode(0);
    }
}
