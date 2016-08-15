<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Purchase response message from checkout.fi
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return $this->data['isRedirect'];
    }

    public function getRedirectUrl()
    {
        return $this->data['location'];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
