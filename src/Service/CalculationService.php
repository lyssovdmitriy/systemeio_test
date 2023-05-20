<?php

declare(strict_types=1);

namespace App\Service {

    use App\Entity\Coupon;
    use App\Entity\Product;

    final class CalculationService
    {

        public function getPrice(Product $product, Coupon $coupon, int $taxPercent): float
        {
            $price = $product->getPrice();

            if ($coupon->isIsFixed()) {
                $price -= $coupon->getFixed();
            } else {
                $price -= $product->getPrice() * $coupon->getPercent() / 100;
            }

            $price += $price * $taxPercent / 100;

            return $price;
        }
    }
}
