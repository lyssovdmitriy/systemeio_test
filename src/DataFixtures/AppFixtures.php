<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => 'Iphone', 'price' => 100,],
            ['name' => 'Наушники', 'price' => 20,],
            ['name' => 'Чехол', 'price' => 10,],
        ];

        foreach ($products as $value) {
            $product = new Product();
            $product->setName($value['name']);
            $product->setPrice($value['price']);
            $manager->persist($product);
        }

        $coupons = [
            ['code' => 'D15', 'is_fixed' => false, 'fixed' => null, 'percent' => 15,],
            ['code' => 'D04', 'is_fixed' => false, 'fixed' => null, 'percent' => 4,],
            ['code' => 'F20', 'is_fixed' => true, 'fixed' => 20, 'percent' => null,],
        ];

        foreach ($coupons as $value) {
            $coupon = new Coupon();
            $coupon->setCode($value['code']);
            $coupon->setIsFixed($value['is_fixed']);
            $coupon->setFixed($value['fixed']);
            $coupon->setPercent($value['percent']);
            $manager->persist($coupon);
        }


        $taxes = [
            ['country_code' => 'DE', 'tax_percent' => 19,],
            ['country_code' => 'IT', 'tax_percent' => 22,],
            ['country_code' => 'GR', 'tax_percent' => 24,],
            ['country_code' => 'FR', 'tax_percent' => 20,],
        ];

        foreach ($taxes as $value) {
            $tax = new Tax();
            $tax->setCountryCode($value['country_code']);
            $tax->setTaxPercent($value['tax_percent']);
            $manager->persist($tax);
        }

        $manager->flush();
    }
}
