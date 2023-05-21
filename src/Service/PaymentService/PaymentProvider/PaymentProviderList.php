<?php

declare(strict_types=1);

namespace App\Service\PaymentService\PaymentProvider {

    enum PaymentProviderList: string
    {
        case PAYPAL = 'paypal';
        case STRIPE = 'stripe';

        public static function values(): array
        {
            return array_column(self::cases(), 'value');
        }
    }
}
