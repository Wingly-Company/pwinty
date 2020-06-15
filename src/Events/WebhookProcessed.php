<?php

namespace Wingly\Pwinty\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookProcessed
{
    use Dispatchable, SerializesModels;

    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
