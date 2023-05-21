<?php

declare(strict_types=1);

namespace App\Requests {

    use Symfony\Component\Form\FormInterface;

    abstract class AbstractRequest
    {

        protected FormInterface $form;
        protected array $errorMessages = [];

        public function isValid(): bool
        {
            if ($this->form->isSubmitted() && $this->form->isValid()) {
                return true;
            }

            foreach ($this->form->getErrors(true, true) as $error) {
                $this->errorMessages[$error->getOrigin()->getName()][] = $error->getMessage();
            }

            return false;
        }

        public function getForm(): FormInterface
        {
            return $this->form;
        }

        public function getErrors(): array
        {
            return $this->errorMessages;
        }

        public function addError(string $fieldName, string $message, string $cause = ''): void
        {
            $this->errorMessages[$cause ?: $fieldName][] = $message;
        }
    }
}
