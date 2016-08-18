<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

/**
 * Purchase complete message for Checkout.fi Omnipay driver. While this is a request
 * it is not sent to the checkout.fi API. Instead it validates the request that came
 * from checkout.fi
 */
class CompletePurchaseRequest extends AbstractRequest
{
    private static $RESPONSE_MAC_FIELDS = array(
        'VERSION', 'STAMP', 'REFERENCE', 'PAYMENT', 'STATUS', 'ALGORITHM'
    );

    // This method of completing purchase is taken from
    // https://github.com/andreas22/omnipay-fasapay
    // that was reviewed by Omnipay people in
    // https://github.com/thephpleague/omnipay/issues/246

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        // Return parameters are in query string
        return $this->httpRequest->query->all();
    }

    /**
     * {@inheritDoc}
     */
    public function sendData($data)
    {
        if (!$this->validateResponseMac($data)) {
            throw new \Exception("Invalid response, either fields tampered or MAC invalid.");
        }

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /**
     * Validate the MAC field from purchase response
     *
     * @todo Use exceptions
     *
     * @param array $parameters Request parameters
     * @return boolean
     */
    private function validateResponseMac(array $parameters = array())
    {
        if (!self::allRequiredKeysExist($parameters)) {
            return false;
        }

        // Only SHA-256 supported
        if ($parameters['ALGORITHM'] !== '3' && $parameters['ALGORITHM'] !== 3) {
            return false;
        }

        $hashString = join(
            '&',
            array_map(function ($field) use (&$parameters) {
                return $parameters[$field];
            }, self::$RESPONSE_MAC_FIELDS)
        );

        $calculatedMac = strtoupper(hash_hmac('sha256', $hashString, $this->getMerchantSecret()));

        return $parameters['MAC'] === $calculatedMac;
    }

    /**
     * Check given array contains all required parameters
     *
     * @param array $parameters Parameters to check
     * @return boolean
     */
    private static function allRequiredKeysExist(array $parameters)
    {
        if (!array_key_exists('MAC', $parameters)) {
            return false;
        }

        foreach (self::$RESPONSE_MAC_FIELDS as $field) {
            if (!array_key_exists($field, $parameters)) {
                return false;
            }
        }

        return true;
    }
}
