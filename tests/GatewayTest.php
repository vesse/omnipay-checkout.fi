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

        // TODO: paypal-gw just does new Gateway... Better?
        $this->gateway = Omnipay::create('CheckoutFi');
    }

    public function testGetName()
    {
        $this->assertEquals('Checkout.fi', $this->gateway->getName());
    }
}
