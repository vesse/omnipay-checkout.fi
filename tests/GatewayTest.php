<?php

namespace Omnipay\CheckoutFi;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Omnipay;

define('CHECKOUT_FI_MERCHANT_ID', '375917');
define('CHECKOUT_FI_HASH_KEY', 'SAIPPUAKAUPPIAS');

class CheckoutFiGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        // If using Omnipay::create('CheckoutFi') mock responses do not work
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->initialize(
            array(
                'merchantId'     => CHECKOUT_FI_MERCHANT_ID,
                'merchantSecret' => CHECKOUT_FI_HASH_KEY,
                'returnUrl'      => 'http://localhost/'
            )
        );

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

        $this->returnParameters = array(
            'VERSION'  => '0001',
            'STAMP'    => '1471325999',
            'REFERENCE'=> '12345678901234567894',
            'PAYMENT'  => '42262586',
            'STATUS'   => '2',
            'ALGORITHM'=> '3',
            'MAC'      => '10C61ED4776D7A73D58B629D4EB4B91667C18E3B74A52EE06C1D0F19B6624C86'
        );
    }

    public function testGetName()
    {
        $this->assertEquals('Checkout.fi', $this->gateway->getName());
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $request = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseRequest', $request);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://payment.checkout.fi/UtNI1UaKX0/fi/new', $response->getRedirectUrl());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $request = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseRequest', $request);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNotNull($response->getMessage());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase($this->returnParameters);
        $response = $request->sendData($this->returnParameters);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response->getTransactionReference(), $this->returnParameters['STAMP']);
    }
}
