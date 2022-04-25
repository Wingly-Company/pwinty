<?php

namespace Wingly\Pwinty\Tests\Unit;

use Wingly\Pwinty\Order;
use Wingly\Pwinty\Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_we_can_check_if_an_order_is_cancelled()
    {
        $order = new Order(['pwinty_status' => 'Cancelled']);

        $this->assertTrue($order->cancelled());
    }
}
