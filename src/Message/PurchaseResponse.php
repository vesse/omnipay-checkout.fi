<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Purchase response message from checkout.fi.
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        // RedirectResponse always returns false
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isRedirect()
    {
        return $this->data['isRedirect'];
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data['location'] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * Get the transaction reference value
     *
     * @return string The value sent to checkout.fi in reference field
     */
    public function getTransactionReference()
    {
        return $this->data['reference'];
    }

    /**
     * Get the HTTP response status code
     *
     * @return int The HTTP status code
     */
    public function getStatusCode()
    {
        return $this->data['statusCode'];
    }

    /**
     * Get possible error message.
     *
     * @return string
     */
    public function getMessage()
    {
        $html = $this->data['body'];
        $matches = array();
        $match = preg_match('/kentässä: (.*?)</', $html, $matches);
        if ($match === 0) {
            return null;
        }
        if ($match === 1) {
            return 'Error in field: ' . $matches[1];
        }
        return 'Error in transaction';
    }
}
