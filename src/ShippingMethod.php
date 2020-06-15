<?php

namespace Wingly\Pwinty;

class ShippingMethod
{
    const Budget = 'Budget';

    const Standard = 'Standard';

    const Express = 'Express';

    const Overnight = 'Overnight';

    public static function validate($shippingMethod)
    {
        if (! in_array($shippingMethod, [
            self::Budget,
            self::Standard,
            self::Express,
            self::Overnight,
        ])) {
            return false;
        }

        return true;
    }
}
