<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $request = $this->getMockRequest();
        $request->shouldReceive('getTestMode')->once()->andReturn(true);
        $response = new PurchaseResponse($request, $httpResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://payment.checkout.fi/UtNI1UaKX0/fi/new', $response->getRedirectUrl());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $request = $this->getMockRequest();
        $request->shouldReceive('getTestMode')->once()->andReturn(true);
        $response = new PurchaseResponse($request, $httpResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNotNull($response->getMessage());
    }
}
