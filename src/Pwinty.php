<?php

namespace Wingly\Pwinty;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Wingly\Pwinty\Exceptions\OrderMissingRequiredParameters;

class Pwinty
{
    protected $client;

    public $apiKey;

    protected $merchantId;

    protected $apiUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setMerchantId(string $merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    public function setApiUrl(string $apiEnv)
    {
        if ($apiEnv === 'production') {
            $this->apiUrl = 'https://api.prodigi.com/v4.0';
        } else {
            $this->apiUrl = 'https://api.sandbox.prodigi.com/v4.0';
        }

        return $this;
    }

    public function getOrder(string $orderId)
    {
        $payload = $this->getRequestPayload();

        $response = $this->client->request('GET', $this->apiUrl."/orders/{$orderId}", $payload);

        return json_decode($response->getBody())->order;
    }

    public function createOrder(array $order)
    {
        $payload = $this->getRequestPayload($order);

        try {
            $response = $this->client->request('POST', $this->apiUrl.'/orders', $payload);
        } catch (ClientException $e) {
            throw new OrderMissingRequiredParameters($e->getMessage());
        }

        return json_decode($response->getBody())->order;
    }

    public function cancelOrder(string $orderId)
    {
        $payload = $this->getRequestPayload();

        $response = $this->client->request('POST', $this->apiUrl."/orders/{$orderId}/actions/cancel", $payload);

        return json_decode($response->getBody());
    }

    protected function getRequestPayload(array $params = null)
    {
        $payload = [
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'Content-type' => 'application/json',
            ],
        ];

        if ($params) {
            $payload['json'] = $params;
        }

        return $payload;
    }
}
