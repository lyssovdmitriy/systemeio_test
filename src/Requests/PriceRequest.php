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
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Validator\Constraints\Choice;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\NotNull;

    final class PriceRequest extends AbstractRequest implements RequestInterface
    {

        public function __construct(FormFactoryInterface $formBuilder)
        {
            $this->form = $formBuilder->createBuilder(FormType::class, null, ['csrf_protection' => false])
                ->setMethod('GET')
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

            $this->populate(Request::createFromGlobals());
        }

        private function populate(Request $request): void
        {
            $this->form->submit([
                'productId' => (int)$request->get('productId'),
                'taxNumber' => $request->get('taxNumber'),
                'paymentProcessor' => $request->get('paymentProcessor'),
                'couponCode' => $request->get('couponCode'),
            ]);
        }
    }
}
