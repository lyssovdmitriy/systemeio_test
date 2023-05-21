<?php

declare(strict_types=1);

namespace App\Validator {

    use Symfony\Component\Validator\Constraint;
    use Symfony\Component\Validator\ConstraintValidator;
    use Symfony\Component\Validator\Exception\UnexpectedTypeException;

    class TaxNumberValidator extends ConstraintValidator
    {
        public function validate($value, Constraint $constraint)
        {
            if (!$constraint instanceof TaxNumber) {
                throw new UnexpectedTypeException($constraint, TaxNumber::class);
            }

            if (!is_string($value)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', '')
                    ->addViolation();

                return;
            }

            $countryCode = substr($value, 0, 2);
            $taxNumberLength = strlen($value);

            $valid = match ($countryCode) {
                'DE' => ($taxNumberLength === 11 && preg_match('/^DE\d{9}$/', $value)),
                'IT' => ($taxNumberLength === 13 && preg_match('/^IT\d{11}$/', $value)),
                'GR' => ($taxNumberLength === 11 && preg_match('/^GR\d{9}$/', $value)),
                'FR' => ($taxNumberLength === 13 && preg_match('/^FR[A-Za-z]{2}\d{9}$/', $value)),
                default => false,
            };

            if (!$valid) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', (string)$value)
                    ->addViolation();
            }
        }
    }
}
