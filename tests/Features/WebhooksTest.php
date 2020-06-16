<?php

namespace Wingly\Pwinty\Tests\Features;

use FeatureTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Wingly\Pwinty\Events\WebhookProcessed;

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

        $this->postJson($this->getSignedUrl(), $data)->assertOk();

        $this->assertTrue($order->fresh()->cancelled());

        Event::assertDispatched(WebhookProcessed::class, function (WebhookProcessed $event) use ($data) {
            return $data === $event->payload;
        });
    }

    public function test_returns_normal_response_for_invalid_orders()
    {
        $this->postJson($this->getSignedUrl(), ['id' => 'foo'])->assertOk();
    }

    public function test_it_doesnt_dispatch_webhook_processed_event_for_invalid_orders()
    {
        Event::fake([WebhookProcessed::class]);

        $this->postJson($this->getSignedUrl(), ['id' => 'foo'])->assertOk();

        Event::assertNotDispatched(WebhookProcessed::class);
    }

    public function test_it_returns_normal_response_when_signature_matches()
    {
        $this->postJson($this->getSignedUrl())->assertOk();
    }

    public function test_it_aborts_when_signature_does_not_match()
    {
        $this->postJson('pwinty/webhook?signature=fail')->assertStatus(403);
    }

    public function test_it_aborts_when_signature_is_missing()
    {
        $this->postJson('pwinty/webhook')->assertStatus(403);
    }

    protected function getSignedUrl()
    {
        return URL::signedRoute('pwinty.webhook');
    }
}
