<?php

namespace Wingly\Pwinty;

class PaymentMethod
{
    const InvoiceMe = 'InvoiceMe';

    const InvoiceRecipient = 'InvoiceRecipient';

    public static function validate($paymentMethod)
    {
        if (! in_array($paymentMethod, [
            self::InvoiceMe,
            self::InvoiceRecipient,
        ])) {
            return false;
        }

        return true;
    }
}
