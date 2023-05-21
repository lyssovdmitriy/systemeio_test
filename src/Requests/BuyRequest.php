<?php

declare(strict_types=1);

namespace App\Requests {

    use App\Service\PaymentService\PaymentProvider\PaymentProviderList;
    use App\Validator\TaxNumber;
    use Symfony\Component\Form\Extension\Core\Type\FormType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormFactoryInterface;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Validator\Constraints\Choice;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\NotNull;

    final class BuyRequest
    {

        private FormInterface $form;
        private array $errorMessages = [];

        public function __construct(FormFactoryInterface $formBuilder)
        {
            $this->form = $formBuilder->createBuilder(FormType::class, null, ['csrf_protection' => false])
                ->setMethod('POST')
                ->add('productId', IntegerType::class, [
                    'constraints' => [
                        new NotNull(),
                        new NotBlank(),
                    ],
                ])
                ->add('taxNumber', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new NotNull(),
                        new TaxNumber()
                    ],
                ])
                ->add('paymentProcessor', TextType::class, [
                    'constraints' => [
                        new NotNull(),
                        new NotBlank(),
                        new Choice([
                            'choices' => PaymentProviderList::values(),
                            'message' => 'Not valid choice. Please select one of the following options: {{ choices }}',
                        ])
                    ]
                ])
                ->add('couponCode', TextType::class)
                ->add('submit', SubmitType::class)
                ->getForm();

            $this->populate();
        }

        private function populate(): void
        {
            $request = Request::createFromGlobals();
            $data = (object)$request->toArray();
            $this->form->submit([
                'productId' => (int)$data->productId,
                'taxNumber' => $data->taxNumber,
                'paymentProcessor' => $data->paymentProcessor,
                'couponCode' => $data->couponCode,

            ]);
        }

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

        public function addError(string $cause, string $message): void
        {
            $this->errorMessages[$cause][] = $message;
        }
    }
}
