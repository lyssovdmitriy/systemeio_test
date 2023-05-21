<?php

declare(strict_types=1);

namespace App\Service\PaymentService\PaymentProvider {

    interface PaymentProviderInterface
    {

        /**
         * Initiate payment
         *
         * @param integer $price (price * 100)
         * @return bool
         */
        public function ProcessPayment(int $price): bool;

        public function getErrors(): array;
    }
}
