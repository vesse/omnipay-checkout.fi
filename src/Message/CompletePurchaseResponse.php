<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Purchase response message from checkout.fi
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['STATUS']) &&
            ($this->data['STATUS'] === '2' || $this->data['STATUS'] === 2);
    }

    public function getTransactionReference()
    {
        return isset($this->data['PAYMENT']) ? $this->data['PAYMENT'] : null;
    }
}
