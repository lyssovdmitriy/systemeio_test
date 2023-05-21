<?php

namespace App\Tests\Validator;

use App\Validator\TaxNumber;
use App\Validator\TaxNumberValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TaxNumberValidatorTest extends TestCase
{
    private TaxNumberValidator $validator;
    private MockObject $context;

    protected function setUp(): void
    {
        $this->validator = new TaxNumberValidator();
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator->initialize($this->context);
    }

    public function testValidTaxNumber(): void
    {
        $constraint = new TaxNumber();

        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->validator->initialize($this->context);

        $this->validator->validate('DE123456789', $constraint);
        $this->validator->validate('IT12345678910', $constraint);
        $this->validator->validate('GR123456789', $constraint);
        $this->validator->validate('FRYY123456789', $constraint);
    }

    public function testInvalidTaxNumber(): void
    {
        $constraint = new TaxNumber();

        $this->context->expects($this->once())
            ->method('buildViolation');

        $this->validator->initialize($this->context);

        $this->validator->validate('INVALID_TAX_NUMBER', $constraint);
    }
}
