<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\CheckoutFi\Message\Exceptions\UnsupportedAlgorithmException;
use Omnipay\CheckoutFi\Message\Exceptions\RequiredFieldMissingException;
use Omnipay\CheckoutFi\Message\Exceptions\ChecksumMismatchException;

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
     *
     * @throws UnsupportedAlgorithmException when provided hash algorithm is not supported
     * @throws RequiredFieldMissingException when required field is missing
     * @throws ChecksumMismatchException when MAC value does not match the calculated value
     */
    public function sendData($data)
    {
        $this->validateResponseMac($data);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /**
     * Validate the MAC field from purchase response
     *
     * @param array $parameters Request parameters
     *
     * @throws UnsupportedAlgorithmException when provided hash algorithm is not supported
     * @throws RequiredFieldMissingException when required field is missing
     * @throws ChecksumMismatchException when MAC value does not match the calculated value
     */
    private function validateResponseMac(array $parameters = array())
    {
        self::validateRequiredKeys($parameters);

        // Only SHA-256 supported
        if ($parameters['ALGORITHM'] !== '3' && $parameters['ALGORITHM'] !== 3) {
            throw new UnsupportedAlgorithmException('Only algorithm 3 is supported');
        }

        $hashString = join(
            '&',
            array_map(function ($field) use (&$parameters) {
                return $parameters[$field];
            }, self::$RESPONSE_MAC_FIELDS)
        );

        $calculatedMac = strtoupper(hash_hmac('sha256', $hashString, $this->getMerchantSecret()));

        if ($parameters['MAC'] !== $calculatedMac) {
            throw new ChecksumMismatchException('MAC value does not match calculated value');
        }
    }

    /**
     * Check given array contains all required parameters
     *
     * @param array $parameters Parameters to check
     *
     * @throws RequiredFieldMissingException when required field is missing
     */
    private static function validateRequiredKeys(array $parameters)
    {
        if (!array_key_exists('MAC', $parameters)) {
            throw new RequiredFieldMissingException('Required field MAC is missing');

        }

        foreach (self::$RESPONSE_MAC_FIELDS as $field) {
            if (!array_key_exists($field, $parameters)) {
                throw new RequiredFieldMissingException('Required field ' . $field . ' is missing');
            }
        }
    }
}
