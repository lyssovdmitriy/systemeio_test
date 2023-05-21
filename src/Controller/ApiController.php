<?php

declare(strict_types=1);

namespace App\Controller {

    use App\Entity\Coupon;
    use App\Entity\Product;
    use App\Entity\Tax;
    use App\Requests\PriceRequest;
    use App\Requests\BuyRequest;
    use App\Service\CalculationService;
    use App\Service\PaymentService\PaymentProvider\PaymentProviderList;
    use App\Service\PaymentService\PaymentService;
    use Doctrine\Persistence\ManagerRegistry;
    use Exception;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route('/api', name: 'api_')]
    class ApiController extends AbstractController
    {
        #[Route('/price', methods: ['GET'])]
        public function price(ManagerRegistry $doctrine, PriceRequest $priceRequest, CalculationService $calculationService): Response
        {
            if ($priceRequest->isValid()) {
                $data = $priceRequest->getForm()->getData();
                /** @var Product $product */
                if (null === $product = $doctrine->getRepository(Product::class)->find($data['productId'])) {
                    $priceRequest->addError('ProductId', 'Product is not exist');
                    return $this->json($priceRequest->getErrors(), 400);
                }

                if (null !== $data['couponCode']) {
                    /** @var Coupon $coupon */
                    if (null === $coupon = $doctrine->getRepository(Coupon::class)->findOneBy(['code' => $data['couponCode']])) {
                        $priceRequest->addError('couponCode', 'Coupon is not valid');
                        return $this->json($priceRequest->getErrors(), 400);
                    }
                } else {
                    $coupon = null;
                }

                /** @var Tax $tax */
                if (null === $tax = $doctrine->getRepository(Tax::class)->findOneBy(['countryCode' => substr($data['taxNumber'], 0, 2)])) {
                    throw new Exception('load fixtures');
                }

                return $this->json($calculationService->getPrice($product, $coupon, $tax->getTaxPercent()), 200);
            } else {
                $errors = $priceRequest->getErrors();
            }

            return $this->json(['errors' => $errors], 400);
        }

        #[Route('/buy', methods: ['POST'])]
        public function buy(ManagerRegistry $doctrine, BuyRequest $buyRequest, CalculationService $calculationService, PaymentService $paymentService): Response
        {
            if ($buyRequest->isValid()) {
                $data = $buyRequest->getForm()->getData();
                /** @var Product $product */
                if (null === $product = $doctrine->getRepository(Product::class)->find($data['productId'])) {
                    $buyRequest->addError('ProductId', 'Product is not exist');
                    return $this->json($buyRequest->getErrors(), 400);
                }

                if (null !== $data['couponCode']) {
                    /** @var Coupon $coupon */
                    if (null === $coupon = $doctrine->getRepository(Coupon::class)->findOneBy(['code' => $data['couponCode']])) {
                        $buyRequest->addError('couponCode', 'Coupon is not valid');
                        return $this->json($buyRequest->getErrors(), 400);
                    }
                } else {
                    $coupon = null;
                }

                /** @var Tax $tax */
                if (null === $tax = $doctrine->getRepository(Tax::class)->findOneBy(['countryCode' => substr($data['taxNumber'], 0, 2)])) {
                    throw new Exception('load fixtures');
                }

                $price = $calculationService->getPrice($product, $coupon, $tax->getTaxPercent());
                return
                    $paymentService->ProcessPayment(PaymentProviderList::from($data['paymentProcessor']), (int)$price * 100)
                    ? $this->json('ok', 200)
                    : $this->json($paymentService->getErrors(), 400);

                // return $this->json(, 200);
            } else {
                $errors = $buyRequest->getErrors();
            }

            return $this->json(['errors' => $errors], 400);
        }
    }
}
