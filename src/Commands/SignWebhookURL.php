<?php

namespace Wingly\Pwinty\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class SignWebhookURL extends Command
{
    protected $signature = 'pwinty:sign';

    protected $description = 'Generates a signed URL to add to the Pwinty dashboard.';

    public function handle()
    {
        $url = URL::signedRoute('pwinty.webhook');

        $this->info("Grab your URL: \"{$url}\"");
    }
}
