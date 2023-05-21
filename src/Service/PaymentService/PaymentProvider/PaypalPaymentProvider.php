<?php

declare(strict_types=1);

namespace App\Service\PaymentService\PaymentProvider {

    use App\Service\PaymentService\PaymentProvider\PaymentProcessor\PaypalPaymentProcessor;
    use App\Service\PaymentService\PaymentProvider\PaymentProviderInterface;

    final class PaypalPaymentProvider extends AbstractPaymentProvider implements PaymentProviderInterface
    {

        private PaypalPaymentProcessor $paypalPaymentProcessor;

        public function __construct()
        {
            $this->paypalPaymentProcessor = new PaypalPaymentProcessor();
        }

        public function ProcessPayment(int $price): bool
        {
            try {
                $this->paypalPaymentProcessor->pay($price);
            } catch (\Throwable $th) {
                $this->addError($th->getMessage());
                return false;
            }

            return true;
        }
    }
}
