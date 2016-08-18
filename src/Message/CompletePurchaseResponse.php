<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Purchase response message from checkout.fi
 */
class CompletePurchaseResponse extends AbstractResponse
{
    private static $SUCCESS_STATUSES = array('2', '4', '5', '6', '7', '8', '9', '10');

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        if (!array_key_exists('STATUS', $this->data)) {
            return false;
        }

        $status = (string) $this->data['STATUS'];
        return in_array($status, self::$SUCCESS_STATUSES, true);
    }

    /**
     * Get the reference value from request
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getField('REFERENCE');
    }

    /**
     * Get the status parameter value
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->getField('STATUS');
    }

    /**
     * Get the value of given field if it exists, or null
     */
    private function getField($field)
    {
        return array_key_exists($field, $this->data) ? $this->data[$field] : null;
    }
}
