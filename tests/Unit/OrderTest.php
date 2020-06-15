<?php

namespace Wingly\Pwinty\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wingly\Pwinty\Exceptions\OrderUpdateFailure;
use Wingly\Pwinty\Order;

class OrderTest extends TestCase
{
    public function test_submitted_orders_cannot_add_images()
    {
        $order = new Order(['pwinty_status' => 'Complete']);

        $this->expectException(OrderUpdateFailure::class);

        $order->addImage('TEST-SKU', 'https://source.unsplash.com/random');
    }

    public function test_we_can_check_if_an_order_is_submitted()
    {
        $order = new Order(['pwinty_status' => 'Submitted']);

        $this->assertTrue($order->submitted());
    }

    public function test_submitted_orders_cannot_be_cancelled()
    {
        $order = new Order(['pwinty_status' => 'Complete']);

        $this->expectException(OrderUpdateFailure::class);

        $order->cancel();
    }

    public function test_we_can_check_if_an_order_is_cancelled()
    {
        $order = new Order(['pwinty_status' => 'Cancelled']);

        $this->assertTrue($order->cancelled());
    }
}
