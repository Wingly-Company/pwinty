<?php

namespace Wingly\Pwinty;

use InvalidArgumentException;

class OrderBuilder
{
    protected $owner;

    protected $merchantOrderId;

    protected $recipientName;

    protected $address1;

    protected $address2;

    protected $addressTownOrCity;

    protected $stateOrCounty;

    protected $postalOrZipCode;

    protected $countryCode;

    protected $preferredShippingMethod;

    protected $payment;

    protected $mobileTelephone;

    protected $telephone;

    protected $email;

    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    public function setMerchantOrderId(string $id)
    {
        $this->merchantOrderId = $id;

        return $this;
    }

    public function setRecipientName(string $name)
    {
        $this->recipientName = $name;

        return $this;
    }

    public function setAddress1(string $address)
    {
        $this->address1 = $address;

        return $this;
    }

    public function setAddress2(string $address)
    {
        $this->address2 = $address;

        return $this;
    }

    public function setAddressTownOrCity(string $townOrCity)
    {
        $this->addressTownOrCity = $townOrCity;

        return $this;
    }

    public function setStateOrCounty(string $stateOrCountry)
    {
        $this->stateOrCounty = $stateOrCountry;

        return $this;
    }

    public function setPostalOrZipCode(string $postalOrZipCode)
    {
        $this->postalOrZipCode = $postalOrZipCode;

        return $this;
    }

    public function setCountryCode(string $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function setPreferredShippingMethod(string $preferredShippingMethod)
    {
        if (! ShippingMethod::validate($preferredShippingMethod)) {
            throw new InvalidArgumentException;
        }

        $this->preferredShippingMethod = $preferredShippingMethod;

        return $this;
    }

    public function setPayment(string $payment)
    {
        if (! PaymentMethod::validate($payment)) {
            throw new InvalidArgumentException;
        }

        $this->payment = $payment;

        return $this;
    }

    public function setMobileTelephone(string $mobile)
    {
        $this->mobileTelephone = $mobile;

        return $this;
    }

    public function setTelephone(string $phone)
    {
        $this->telephone = $phone;

        return $this;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function create() : Order
    {
        $payload = $this->buildPayload();

        $pwintyOrder = app(Pwinty::class)->createOrder($payload);

        $order = $this->owner->orders()->create([
            'pwinty_id' => $pwintyOrder->id,
            'pwinty_status' => $pwintyOrder->status,
        ]);

        return $order;
    }

    protected function buildPayload()
    {
        $payload = array_filter([
            'merchantOrderId' => $this->merchantOrderId,
            'recipientName' => $this->recipientName,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'addressTownOrCity' => $this->addressTownOrCity,
            'stateOrCounty' => $this->stateOrCounty,
            'postalOrZipCode' => $this->postalOrZipCode,
            'countryCode' => $this->countryCode,
            'preferredShippingMethod' => $this->preferredShippingMethod,
            'payment' => $this->payment,
            'mobileTelephone' => $this->mobileTelephone,
            'telephone' => $this->telephone,
            'email' => $this->email,
        ]);

        return $payload;
    }
}
