<?php

namespace Omnipay\CheckoutFi;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Omnipay;
use Omnipay\CheckoutFi\Exceptions\UnsupportedAlgorithmException;
use Omnipay\CheckoutFi\Exceptions\RequiredFieldMissingException;
use Omnipay\CheckoutFi\Exceptions\ChecksumMismatchException;

define('CHECKOUT_FI_MERCHANT_ID', '375917');
define('CHECKOUT_FI_HASH_KEY', 'SAIPPUAKAUPPIAS');

#http://localhost:3000/?VERSION=0001
# STAMP=1471518672
# REFERENCE=12345678901234567894
# PAYMENT=42365465
# STATUS=2
# ALGORITHM=3
# MAC=AA038570C1E4C4A6BDB61A31A0A7F8CF25D562DD834A2152E81D387441068516

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

        $this->purchaseParameters = array(
            'STAMP'        => time(),
            'AMOUNT'       => '1200',
            'REFERENCE'    => '123456793',
            'DELIVERYDATE' => '20160910',
            'FIRSTNAME'    => 'Paying',
            'FAMILYNAME'   => 'Customer',
            'ADDRESS'      => 'Street address 114',
            'POSTCODE'     => '33100',
            'POSTOFFICE'   => 'Tampere'
        );

        $this->complatePurchaseParameters = array(
            'VERSION'  => '0001',
            'STAMP'    => '1471325999',
            'REFERENCE'=> '12345678901234567894',
            'PAYMENT'  => '42262586',
            'STATUS'   => '2',
            'ALGORITHM'=> '3',
            'MAC'      => '10C61ED4776D7A73D58B629D4EB4B91667C18E3B74A52EE06C1D0F19B6624C86'
        );

        $this->refundParameters = array(
            'STAMP'       => time(),
            'REFUNDSTAMP' => '1471523196',
            'REFERENCE'   => '12345678901234567894',
            'AMOUNT'      => '1200',
            'EMAIL'       => 'someone@example.com'
        );
    }

    public function testGetName()
    {
        $this->assertSame('Checkout.fi', $this->gateway->getName());
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $request = $this->gateway->purchase($this->purchaseParameters);

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseRequest', $request);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertSame('https://payment.checkout.fi/UtNI1UaKX0/fi/new', $response->getRedirectUrl());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $request = $this->gateway->purchase($this->purchaseParameters);

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseRequest', $request);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertSame('Error in field: MAC', $response->getMessage());
    }

    public function testPurchaseFailureNotMatchingRegexp()
    {
        $this->setMockHttpResponse('PurchaseFailureNotMatchingRegexp.txt');

        $response = $this->gateway->purchase($this->purchaseParameters)->send();

        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertContains('Error in transaction', $response->getMessage());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase($this->complatePurchaseParameters);
        $response = $request->sendData($this->complatePurchaseParameters);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($response->getTransactionReference(), $this->complatePurchaseParameters['REFERENCE']);
    }

    public function testCompletePurchaseHashMismatch()
    {
        $this->setExpectedException(ChecksumMismatchException::class);
        $this->complatePurchaseParameters['MAC'] = '123';
        $request = $this->gateway->completePurchase($this->complatePurchaseParameters);
        $request->sendData($this->complatePurchaseParameters);
    }

    public function testCompletePurchaseFieldMissing()
    {
        $this->setExpectedException(RequiredFieldMissingException::class);
        unset($this->complatePurchaseParameters['REFERENCE']);
        $request = $this->gateway->completePurchase($this->complatePurchaseParameters);
        $request->sendData($this->complatePurchaseParameters);
    }

    public function testCompletePurchaseMacFieldMissing()
    {
        $this->setExpectedException(RequiredFieldMissingException::class);
        unset($this->complatePurchaseParameters['MAC']);
        $request = $this->gateway->completePurchase($this->complatePurchaseParameters);
        $request->sendData($this->complatePurchaseParameters);
    }

    public function testCompletePurchaseUnsupportedAlgorithm()
    {
        $this->setExpectedException(UnsupportedAlgorithmException::class);
        $this->complatePurchaseParameters['ALGORITHM'] = '1';
        $request = $this->gateway->completePurchase($this->complatePurchaseParameters);
        $request->sendData($this->complatePurchaseParameters);
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $request = $this->gateway->refund($this->refundParameters);

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\RefundRequest', $request);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\CheckoutFi\Message\RefundResponse', $response);
        $this->assertTrue($response->isSuccessful());
    }

}
