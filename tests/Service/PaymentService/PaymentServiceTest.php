<?php

namespace App\Tests\Service\PaymentService;

use App\Service\PaymentService\PaymentProvider\PaymentProviderList;
use App\Service\PaymentService\PaymentProvider\PaypalPaymentProvider;
use App\Service\PaymentService\PaymentProvider\StripePaymentProvider;
use App\Service\PaymentService\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    public function testGetPaymentProviderByName(): void
    {
        $paymentService = new PaymentService();
        $reflection = new \ReflectionClass(PaymentService::class);
        $method = $reflection->getMethod('getPaymentProviderByName');
        $method->setAccessible(true);

        $paypalProvider = $method->invoke($paymentService, PaymentProviderList::PAYPAL);
        $stripeProvider = $method->invoke($paymentService, PaymentProviderList::STRIPE);

        $this->assertInstanceOf(PaypalPaymentProvider::class, $paypalProvider);
        $this->assertInstanceOf(StripePaymentProvider::class, $stripeProvider);
    }
}
