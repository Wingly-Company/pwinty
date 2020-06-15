<?php

use Wingly\Pwinty\Exceptions\OrderMissingRequiredParameters;
use Wingly\Pwinty\Exceptions\OrderUpdateFailure;
use Wingly\Pwinty\PaymentMethod;
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
            ->setPayment(PaymentMethod::InvoiceMe)
            ->setMobileTelephone('+3373132112')
            ->setTelephone('+3373132112')
            ->setEmail($user->email)
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
            ->setCountryCode('FR')
            ->create();

        $this->assertEquals(1, $user->orders->count());
        $this->assertNotNull($user->orders->first()->pwinty_id);
    }

    public function test_can_add_images_to_orders()
    {
        $user = $this->createUser();

        $order = $user->newOrder()
            ->setRecipientName('Dimitris Karapanos')
            ->setCountryCode('FR')
            ->create();

        $order->addImage(getenv('PWINTY_SKU'), 'https://source.unsplash.com/random');

        $pwintyOrder = $order->asPwintyOrder();

        $this->assertEquals(1, count($pwintyOrder->images));
    }

    public function test_can_submit_orders()
    {
        $user = $this->createUser();

        $order = $user->newOrder()
            ->setRecipientName('Dimitris Karapanos')
            ->setAddress1('Rue de Rivoli')
            ->setAddressTownOrCity('Paris')
            ->setPostalOrZipCode('75001')
            ->setCountryCode('FR')
            ->create()
            ->addImage(getenv('PWINTY_SKU'), 'https://source.unsplash.com/random')
            ->submit();

        $this->assertTrue($order->submitted());
    }

    public function test_invalid_orders_cant_be_submitted()
    {
        $user = $this->createUser();

        $this->expectException(OrderUpdateFailure::class);

        $order = $user->newOrder()
            ->setRecipientName('Dimitris Karapanos')
            ->setCountryCode('FR')
            ->create()
            ->submit();

        $this->assertEquals(0, $user->orders->count());
    }

    public function test_can_cancel_orders()
    {
        $user = $this->createUser();

        $order = $user->newOrder()
            ->setRecipientName('Dimitris Karapanos')
            ->setCountryCode('FR')
            ->create()
            ->cancel();

        $this->assertTrue($order->cancelled());
    }
}
