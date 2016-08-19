<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use \Omnipay\CheckoutFi\Gateway;

/**
 * Status query request message for Checkout.fi Omnipay driver.
 */
class QueryRequest extends AbstractAPIRequest
{
    /**
     * {@inheritDoc}
     *
     * Note: All parameters are truncated to their respective maximum lengths.
     */
    public function getData()
    {
        $this->validate('stamp', 'amount', 'reference');

        $data = array(
            'VERSION'   => '0001',
            'STAMP'     => self::ensureLength($this->getStamp(), 20),
            'REFERENCE' => self::ensureLength($this->getReference(), 20),
            'MERCHANT'  => self::ensureLength($this->getMerchantId(), 20),
            'AMOUNT'    => self::ensureLength($this->getAmount(), 8),
            'CURRENCY'  => 'EUR',
            'FORMAT'    => '1',
            'ALGORITHM' => '1'
        );

        $hashString  = join('+', $data) . '+' . $this->getMerchantSecret();
        $data['MAC'] = strtoupper(md5($hashString));

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post(Gateway::getQueryUrl(), null, $data)->send();

        try {
            $xml = simplexml_load_string($httpResponse->getBody(true));
        } catch (\Exception $e) {
            $xml = false;
        }

        return $this->response = new QueryResponse($this, array(
            'xml'        => $xml,
            'statusCode' => $httpResponse->getStatusCode()
        ));
    }
}
