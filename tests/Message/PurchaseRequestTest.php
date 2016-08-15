<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'VERSION'       => '0001',
                'STAMP'         => '123123123123',
                'AMOUNT'        => '1200',
                'REFERENCE'     => '1234567893',
                'MESSAGE'       => 'Message',
                'LANGUAGE'      => 'FI',
                'RETURN'        => 'http://localhost/',
                'CANCEL'        => 'http://localhost/',
                'REJECT'        => 'http://localhost/',
                'DELAYED'       => 'http://localhost/',
                'COUNTRY'       => 'FIN',
                'CURRENCY'      => 'EUR',
                'DEVICE'        => '1',
                'CONTENT'       => '1',
                'TYPE'          => '0',
                'ALGORITHM'     => '3',
                'DELIVERY_DATE' => '20160815',
                'FIRSTNAME'     => 'Paying',
                'FAMILYNAME'    => 'Customer',
                'ADDRESS'       => 'Streetaddress 123',
                'POSTCODE'      => '33100',
                'POSTOFFICE'    => 'Tampere',
                'EMAIL'         => 'customer@example.com',
                'PHONE'         => '+358123123123'
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEquals('0001',                 $data['VERSION']);
        $this->assertEquals('123123123123',         $data['STAMP']);
        $this->assertEquals('1200',                 $data['AMOUNT']);
        $this->assertEquals('1234567893',           $data['REFERENCE']);
        $this->assertEquals('Message',              $data['MESSAGE']);
        $this->assertEquals('FI',                   $data['LANGUAGE']);
        $this->assertEquals('FIN',                  $data['COUNTRY']);
        $this->assertEquals('EUR',                  $data['CURRENCY']);
        $this->assertEquals('1',                    $data['DEVICE']);
        $this->assertEquals('1',                    $data['CONTENT']);
        $this->assertEquals('0',                    $data['TYPE']);
        $this->assertEquals('3',                    $data['ALGORITHM']);
        $this->assertEquals('20160815',             $data['DELIVERY_DATE']);
        $this->assertEquals('Paying',               $data['FIRSTNAME']);
        $this->assertEquals('Customer',             $data['FAMILYNAME']);
        $this->assertEquals('Streetaddress 123',    $data['ADDRESS']);
        $this->assertEquals('33100',                $data['POSTCODE']);
        $this->assertEquals('Tampere',              $data['POSTOFFICE']);
        $this->assertEquals('customer@example.com', $data['EMAIL']);
        $this->assertEquals('+358123123123',        $data['PHONE']);

        // These come from the Gateway and are not set if here since the
        // request is created manually.
        //$this->assertEquals('375917',               $data['MERCHANT']);
        //$this->assertEquals('http://localhost/',    $data['RETURN']);
        //$this->assertEquals('http://localhost/',    $data['CANCEL']);
        //$this->assertEquals('http://localhost/',    $data['REJECT']);
        //$this->assertEquals('http://localhost/',    $data['DELAYED']);

    }
}
