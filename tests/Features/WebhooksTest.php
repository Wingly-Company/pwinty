<?php

namespace Wingly\Pwinty\Tests\Features;

use FeatureTestCase;
use Wingly\Pwinty\Events\WebhookProcessed;
use Illuminate\Support\Facades\Event;

class WebhooksTest extends FeatureTestCase
{
    public function test_orders_are_updated()
    {
        Event::fake([WebhookProcessed::class]);

        $user = $this->createUser();

        $order = $user->orders()->create([
            'pwinty_id' => 'foo',
            'pwinty_status' => 'NotYetSubmitted',
        ]);

        $data = [
            'id' => $order->pwinty_id,
            'status' => 'Cancelled',
        ];

        $this->postJson('pwinty/webhook', $data)->assertOk();

        $this->assertTrue($order->fresh()->cancelled());

        Event::assertDispatched(WebhookProcessed::class, function (WebhookProcessed $event) use ($data) {
            return $data === $event->payload;
        });
    }

    public function test_returns_normal_response_for_invalid_orders()
    {
        $this->postJson('pwinty/webhook', ['id' => 'foo'])->assertOk();
    }

    public function test_it_doesnt_dispatch_webhook_processed_event_for_invalid_orders()
    {
        Event::fake([WebhookProcessed::class]);

        $this->postJson('pwinty/webhook', ['id' => 'foo'])->assertOk();

        Event::assertNotDispatched(WebhookProcessed::class);
    }
}
