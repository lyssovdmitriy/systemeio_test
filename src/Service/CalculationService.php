<?php

declare(strict_types=1);

namespace App\Service {

    use App\Entity\Coupon;
    use App\Entity\Product;

    final class CalculationService
    {

        public function getPrice(Product $product, ?Coupon $coupon, int $taxPercent): string
        {
            $price = $product->getPrice();

            if (null !== $coupon) {
                if ($coupon->isIsFixed()) {
                    $price -= $coupon->getFixed();
                } else {
                    $price -= $product->getPrice() * $coupon->getPercent() / 100;
                }
            }

            $price += $price * $taxPercent / 100;

            return number_format($price, 2);
        }
    }
}
