<?php

declare(strict_types=1);

namespace App\Service\PaymentService\PaymentProvider {

    use App\Service\PaymentService\PaymentProvider\PaymentProcessor\StripePaymentProcessor;
    use App\Service\PaymentService\PaymentProvider\PaymentProviderInterface;

    final class StripePaymentProvider extends AbstractPaymentProvider implements PaymentProviderInterface
    {

        private StripePaymentProcessor $stripePaymentProcessor;

        public function __construct()
        {
            $this->stripePaymentProcessor = new StripePaymentProcessor();
        }

        public function ProcessPayment(int $price): bool
        {
            return $this->stripePaymentProcessor->processPayment($price);
        }
    }
}
