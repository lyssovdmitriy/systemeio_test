<?php

declare(strict_types=1);

namespace App\Service\PaymentService {

    use App\Service\PaymentService\PaymentProvider\PaymentProviderInterface;
    use App\Service\PaymentService\PaymentProvider\PaymentProviderList;
    use App\Service\PaymentService\PaymentProvider\PaypalPaymentProvider;
    use App\Service\PaymentService\PaymentProvider\StripePaymentProvider;

    final class PaymentService
    {

        private array $errors = [];

        const ERROR_CAUSE_TEMPLATE = '%s payment error';

        public function ProcessPayment(PaymentProviderList $paymentProviderName, int $price): bool
        {
            $paymentProvider = $this->getPaymentProviderByName($paymentProviderName);
            if (!$paymentProvider->ProcessPayment($price)) {
                $this->setErrors([sprintf(static::ERROR_CAUSE_TEMPLATE, $paymentProviderName->value) => $paymentProvider->getErrors()]);
                return false;
            }

            return true;
        }

        public function getErrors(): array
        {
            return $this->errors;
        }

        public function setErrors(array $errors): void
        {
            $this->errors = $errors;
        }

        private function getPaymentProviderByName(PaymentProviderList $paymentProviderName): PaymentProviderInterface
        {
            return match ($paymentProviderName) {
                PaymentProviderList::PAYPAL => new PaypalPaymentProvider(),
                PaymentProviderList::STRIPE => new StripePaymentProvider(),
            };
        }
    }
}
