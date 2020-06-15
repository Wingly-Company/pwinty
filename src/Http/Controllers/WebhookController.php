<?php

namespace Wingly\Pwinty\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Wingly\Pwinty\Events\WebhookProcessed;
use Wingly\Pwinty\Order;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        try {
            $order = Order::where('pwinty_id', $payload['id'])->firstOrFail();

            $order->pwinty_status = $payload['status'];

            $order->save();

            WebhookProcessed::dispatch($payload);

            return new Response('Webhook Processed', 200);
        } catch (Exception $e) {
            return new Response;
        }
    }
}
