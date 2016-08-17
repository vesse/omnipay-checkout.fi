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
        return array_key_exists('STATUS', $this->data) &&
            ($this->data['STATUS'] === '2' || $this->data['STATUS'] === 2);
    }

    public function getTransactionReference()
    {
        return $this->getField('STAMP');
    }

    public function getPaymentStatus()
    {
        return $this->getField('STATUS');
    }

    private function getField($field)
    {
        return array_key_exists($field, $this->data) ? $this->data[$field] : null;
    }
}
