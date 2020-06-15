<?php

namespace Wingly\Pwinty\Exceptions;

use Exception;
use Wingly\Pwinty\Order;

class OrderUpdateFailure extends Exception
{
    public static function nonUpdatableStatus(Order $order)
    {
        return new static(
            "The order \"{$order->pwinty_id}\" cannot be updated because its status is \"{$order->pwinty_status}\"."
        );
    }

    public static function invalidOrder(Order $order)
    {
        return new static(
            "The order \"{$order->pwinty_id}\" cannot be submitted."
        );
    }
}
