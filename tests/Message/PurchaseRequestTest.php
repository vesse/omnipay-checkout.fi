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
        // The keys with value 'xxxx' are hard-coded in the implementation
        // and value given here should not overwrite the value set in the request
        $this->request->initialize(
            array(
                'version'       => 'xxxx',
                'stamp'         => '123123123123',
                'amount'        => '1200',
                'reference'     => '0001234567893',
                'message'       => 'Message',
                'language'      => 'FI',
                'return'        => 'http://localhost/',
                'cancel'        => 'http://localhost/',
                'reject'        => 'http://localhost/',
                'delayed'       => 'http://localhost/',
                'country'       => 'FIN',
                'currency'      => 'xxxx',
                'device'        => 'xxxx',
                'content'       => '1',
                'type'          => 'xxxx',
                'algorithm'     => 'xxxx',
                'deliveryDate'  => '20160815',
                'firstName'     => 'Paying',
                'familyName'    => 'Customer',
                'address'       => 'Streetaddress 123',
                'postCode'      => '33100',
                'postOffice'    => 'Tampere',
                'email'         => 'customer@example.com',
                'phone'         => '+358123123123'
            )
        );
    }

    public function testGetData()
    {
        $this->request->setMerchantId('123123');
        $this->request->setReturnUrl('http://localhost/');

        $data = $this->request->getData();

        $this->assertSame('123123',               $data['MERCHANT']);
        $this->assertSame('http://localhost/',    $data['RETURN']);
        $this->assertSame('http://localhost/',    $data['CANCEL']);
        $this->assertSame('http://localhost/',    $data['REJECT']);
        $this->assertSame('http://localhost/',    $data['DELAYED']);

        $this->assertSame('0001',                 $data['VERSION']);
        $this->assertSame('123123123123',         $data['STAMP']);
        $this->assertSame('1200',                 $data['AMOUNT']);
        $this->assertSame('0001234567893',        $data['REFERENCE']);
        $this->assertSame('Message',              $data['MESSAGE']);
        $this->assertSame('FI',                   $data['LANGUAGE']);
        $this->assertSame('FIN',                  $data['COUNTRY']);
        $this->assertSame('EUR',                  $data['CURRENCY']);
        $this->assertSame('1',                    $data['DEVICE']);
        $this->assertSame('1',                    $data['CONTENT']);
        $this->assertSame('0',                    $data['TYPE']);
        $this->assertSame('3',                    $data['ALGORITHM']);
        $this->assertSame('20160815',             $data['DELIVERY_DATE']);
        $this->assertSame('Paying',               $data['FIRSTNAME']);
        $this->assertSame('Customer',             $data['FAMILYNAME']);
        $this->assertSame('Streetaddress 123',    $data['ADDRESS']);
        $this->assertSame('33100',                $data['POSTCODE']);
        $this->assertSame('Tampere',              $data['POSTOFFICE']);
        $this->assertSame('customer@example.com', $data['EMAIL']);
        $this->assertSame('+358123123123',        $data['PHONE']);

        $this->assertSame('F93C1D28E78737147CCE9A0DF0EFDBEB', $data['MAC']);
    }
}
