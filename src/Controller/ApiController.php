<?php

declare(strict_types=1);

namespace App\Controller {

    use App\Entity\Coupon;
    use App\Entity\Product;
    use App\Entity\Tax;
    use App\Requests\PriceRequest;
    use App\Service\CalculationService;
    use Doctrine\Persistence\ManagerRegistry;
    use Exception;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route('/api', name: 'api_')]
    class ApiController extends AbstractController
    {
        #[Route('/price', name: 'get_price', methods: ['GET'])]
        public function index(ManagerRegistry $doctrine, PriceRequest $priceRequest, CalculationService $calculationService): Response
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
    }
}
