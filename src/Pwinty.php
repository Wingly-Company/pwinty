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
            $this->apiUrl = 'https://api.pwinty.com/v3.0';
        } else {
            $this->apiUrl = 'https://sandbox.pwinty.com/v3.0';
        }

        return $this;
    }

    public function getOrder(int $orderId)
    {
        $payload = $this->getRequestPayload();

        $response = $this->client->request('GET', $this->apiUrl."/orders/{$orderId}", $payload);

        return json_decode($response->getBody())->data;
    }

    public function createOrder(array $order)
    {
        $payload = $this->getRequestPayload($order);

        try {
            $response = $this->client->request('POST', $this->apiUrl.'/orders', $payload);
        } catch (ClientException $e) {
            throw new OrderMissingRequiredParameters($e->getMessage());
        }

        return json_decode($response->getBody())->data;
    }

    public function addImage(int $orderId, array $image)
    {
        $payload = $this->getRequestPayload($image);

        $response = $this->client->request('POST', $this->apiUrl."/orders/{$orderId}/images", $payload);

        return json_decode($response->getBody())->data;
    }

    public function checkSubmissionStatus(int $orderId)
    {
        $payload = $this->getRequestPayload();

        $response = $this->client->request('GET', $this->apiUrl."/orders/{$orderId}/SubmissionStatus", $payload);

        return json_decode($response->getBody())->data;
    }

    public function updateStatus(int $orderId, string $status)
    {
        $payload = $this->getRequestPayload(['status' => $status]);

        $response = $this->client->request('POST', $this->apiUrl."/orders/{$orderId}/status", $payload);

        return json_decode($response->getBody())->data;
    }

    protected function getRequestPayload(array $params = null)
    {
        $payload = [
            'headers' => [
                'X-Pwinty-REST-API-Key' => $this->apiKey,
                'X-Pwinty-MerchantId' => $this->merchantId,
                'Content-type' => 'application/json',
            ]
        ];

        if ($params) {
            $payload['json'] = $params;
        }

        return $payload;
    }
}
