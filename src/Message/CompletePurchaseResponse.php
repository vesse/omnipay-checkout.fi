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
    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return array_key_exists('STATUS', $this->data) &&
            ($this->data['STATUS'] === '2' || $this->data['STATUS'] === 2);
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
