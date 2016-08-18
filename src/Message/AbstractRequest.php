<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * Base class for all requests
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * Get the merchant ID
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set the merchant ID
     *
     * @param string $merchantId Merchant ID
     */
    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchantId', self::toString($merchantId));
    }

    /**
     * Get the merchant secret
     *
     * @return string
     */
    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    /**
     * Set the merchant secret
     *
     * @param string $merchantSecret Merchant secret
     */
    public function setMerchantSecret($merchantSecret)
    {
        $this->setParameter('merchantSecret', $merchantSecret);
    }

    /**
     * Cast non-null value to string
     *
     * @param mixed $value Value to cast
     * @return string or null
     */
    protected static function toString($value)
    {
        if (is_null($value)) {
            return null;
        }

        return (string) $value;
    }
}
