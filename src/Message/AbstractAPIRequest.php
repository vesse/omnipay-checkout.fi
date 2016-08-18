<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

/**
 * Base request class with common methods for values needed when sending
 * HTTP requests to checkout.fi (purchase, refund, query)
 */
abstract class AbstractAPIRequest extends AbstractRequest
{
    /**
     * Get the stamp value
     *
     * @return string
     */
    public function getStamp()
    {
        return $this->getParameter('stamp');
    }

    /**
     * Set the stamp value
     *
     * @param string $stamp Stamp value
     */
    public function setStamp($stamp)
    {
        $this->setParameter('stamp', $stamp);
    }

    /**
     * Get the payment amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set the payment amount
     *
     * @param mixed $amount Amount in cents
     */
    public function setAmount($amount)
    {
        $this->setParameter('amount', self::toString($amount));
    }

    /**
     * Get the payment reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->getParameter('reference');
    }

    /**
     * Set the payment reference
     *
     * @param string $refernce Set the payment reference
     */
    public function setReference($reference)
    {
        $this->setParameter('reference', $reference);
    }
}
