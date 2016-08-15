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

        $this->gateway = Omnipay::create('CheckoutFi');

        $this->gateway->initialize(
            array(
                'merchantId' => CHECKOUT_FI_MERCHANT_ID,
                'merchantSecret' => CHECKOUT_FI_HASH_KEY
            )
        );
    }

    public function testGetName()
    {
        $this->assertEquals('Checkout.fi', $this->gateway->getName());
    }

    // TODO: Set all possible values and validate the values in request with getters
    public function testPurchase()
    {
        $request = $this->gateway->purchase(
            array(
                'STAMP' => time(),
                'AMOUNT' => '1200',
                'REFERENCE' => '123456793',
                'DELIVERY_DATE' => '20160910',
                'FIRSTNAME' => 'Paying',
                'FAMILYNAME' => 'Customer',
                'ADDRESS' => 'Street address 114',
                'POSTCODE' => '33100',
                'POSTOFFICE' => 'Tampere'
            )
        );

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseRequest', $request);

        $response = $request->send();

        $this->assertRegExp('/\/fi\/new$/', $response->getRedirectUrl());
    }
}
