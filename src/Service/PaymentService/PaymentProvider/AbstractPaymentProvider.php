<?php

declare(strict_types=1);

namespace App\Service\PaymentService\PaymentProvider {

    abstract class AbstractPaymentProvider
    {

        protected array $errors;

        public function getErrors(): array
        {
            return $this->errors;
        }

        protected function addError(string $error): void
        {
            $this->errors[] = $error;
        }
    }
}
