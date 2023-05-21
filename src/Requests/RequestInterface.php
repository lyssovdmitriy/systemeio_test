<?php

declare(strict_types=1);

namespace App\Requests {

    use Symfony\Component\Form\FormInterface;

    interface RequestInterface
    {
        public function isValid(): bool;
        public function getForm(): FormInterface;
        public function getErrors(): array;
        public function addError(string $fieldName, string $message, string $cause = ''): void;
    }
}
