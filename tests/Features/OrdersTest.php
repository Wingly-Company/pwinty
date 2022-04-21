<?php

use Wingly\Pwinty\Exceptions\OrderMissingRequiredParameters;
use Wingly\Pwinty\ShippingMethod;

class OrdersTest extends FeatureTestCase
{
    public function test_can_create_orders()
    {
        $user = $this->createUser();

        $user->newOrder()
            ->setMerchantOrderId('AX-123')
            ->setRecipientName('Dimitris Karapanos')
            ->setAddress1('Rue de Rivoli')
            ->setAddress2('Rue Sainte Anne')
            ->setAddressTownOrCity('Paris')
            ->setPostalOrZipCode('75001')
            ->setCountryCode('FR')
            ->setPreferredShippingMethod(ShippingMethod::Standard)
            ->setMobileTelephone('+3373132112')
            ->setTelephone('+3373132112')
            ->setEmail($user->email)
            ->setImage('https://source.unsplash.com/random', getenv('PWINTY_SKU'))
            ->create();

        $this->assertEquals(1, $user->orders->count());
        $this->assertNotNull($user->orders->first()->pwinty_id);
    }

    public function test_missing_required_parameters_results_in_an_exception()
    {
        $user = $this->createUser();

        $this->expectException(OrderMissingRequiredParameters::class);

        $user->newOrder()
            ->setMerchantOrderId('AX-123')
            ->create();

        $this->assertEquals(0, $user->orders->count());
    }

    public function test_can_create_orders_with_the_minimum_required_parameters()
    {
        $user = $this->createUser();

        $user->newOrder()
            ->setRecipientName('Dimitris Karapanos')
            ->setAddress1('Rue de Rivoli')
            ->setPostalOrZipCode('75001')
            ->setAddressTownOrCity('Paris')
            ->setCountryCode('FR')
            ->setPreferredShippingMethod(ShippingMethod::Standard)
            ->setImage('https://source.unsplash.com/random', getenv('PWINTY_SKU'))
            ->create();

        $this->assertEquals(1, $user->orders->count());
        $this->assertNotNull($user->orders->first()->pwinty_id);
    }

    public function test_can_cancel_orders()
    {
        $user = $this->createUser();

        $order = $user->newOrder()
            ->setRecipientName('Dimitris Karapanos')
            ->setAddress1('Rue de Rivoli')
            ->setPostalOrZipCode('75001')
            ->setAddressTownOrCity('Paris')
            ->setCountryCode('FR')
            ->setPreferredShippingMethod(ShippingMethod::Standard)
            ->setImage('https://source.unsplash.com/random', getenv('PWINTY_SKU'))
            ->create()
            ->cancel();

        $this->assertTrue($order->cancelled());
    }
}
