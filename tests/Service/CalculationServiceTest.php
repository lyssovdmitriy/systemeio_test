<?php

namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Service\CalculationService;
use PHPUnit\Framework\TestCase;

class CalculationServiceTest extends TestCase
{
    public function testGetPriceWithoutCouponAndTax(): void
    {
        $calculationService = new CalculationService();

        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(10.0);

        $taxPercent = 0;

        $price = $calculationService->getPrice($product, null, $taxPercent);
        $this->assertEquals('10.00', $price);
    }

    public function testGetPriceWithFixedCouponAndTax(): void
    {
        $calculationService = new CalculationService();

        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(10.0);

        $coupon = new Coupon();
        $coupon->setFixed(5.0);
        $coupon->setIsFixed(true);

        $taxPercent = 10;

        $price = $calculationService->getPrice($product, $coupon, $taxPercent);

        $this->assertEquals('5.50', $price);
    }

    public function testGetPriceWithPercentCouponAndTax(): void
    {
        $calculationService = new CalculationService();
        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(10.0);

        $coupon = new Coupon();
        $coupon->setPercent(20.0);
        $coupon->setIsFixed(false);

        $taxPercent = 10;

        $price = $calculationService->getPrice($product, $coupon, $taxPercent);

        $this->assertEquals('8.80', $price);
    }
}
