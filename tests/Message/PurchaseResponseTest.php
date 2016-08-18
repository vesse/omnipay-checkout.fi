<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\CheckoutFi\Gateway;

class PurchaseResponseTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'STAMP'         => time(),
            'AMOUNT'        => '1200',
            'REFERENCE'     => '123456793',
            'DELIVERY_DATE' => '20160910',
            'FIRSTNAME'     => 'Paying',
            'FAMILYNAME'    => 'Customer',
            'ADDRESS'       => 'Street address 114',
            'POSTCODE'      => '33100',
            'POSTOFFICE'    => 'Tampere'
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('https://payment.checkout.fi/UtNI1UaKX0/fi/new', $response->getRedirectUrl());
        $this->assertNull($response->getMessage());
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame($this->options['REFERENCE'], $response->getTransactionReference());

        // For coverage
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNotNull($response->getMessage());
        $this->assertSame(200, $response->getStatusCode());
    }
}
