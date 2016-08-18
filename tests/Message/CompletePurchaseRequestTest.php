<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    private static $SUCCESS_STATUSES = array(2, 4, 5, 6, 7, 8, 9, 10);

    public function setUp()
    {
        parent::setUp();

        $this->params = array(
            'VERSION'  => '0001',
            'STAMP'    => '1471325999',
            'REFERENCE'=> '12345678901234567894',
            'PAYMENT'  => '42262586',
            'STATUS'   => '2',
            'ALGORITHM'=> '3'
        );

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testIsSuccessful()
    {
        for ($i = -10; $i <= 10; $i++) {
            $this->params['STATUS'] = (string) $i;
            $this->params['MAC'] = self::calculateMac($this->params);
            $response = new CompletePurchaseResponse($this->request, $this->params);
            if (in_array($i, self::$SUCCESS_STATUSES)) {
                $this->assertTrue($response->isSuccessful());
            } else {
                $this->assertFalse($response->isSuccessful());
            }

            $this->assertSame($this->params['STATUS'], $response->getPaymentStatus());
        }

        $response = new CompletePurchaseResponse($this->request, array());
        $this->assertFalse($response->isSuccessful());
    }

    private static function calculateMac($params) {
        $str = join('+', $params) . '+' . CHECKOUT_FI_HASH_KEY;
        return strtoupper(md5($str));
    }
}
