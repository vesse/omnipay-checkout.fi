<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;


use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    public function getPaymentUrl()
    {
        return $this->getParameter('paymentUrl');
    }

    public function setPaymentUrl($paymentUrl)
    {
        $this->setParameter('paymentUrl', $paymentUrl);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    public function setMerchantSecret($merchantSecret)
    {
        $this->setParameter('merchantSecret', $merchantSecret);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function setReturnUrl($returnUrl)
    {
        $this->setParameter('returnUrl', $returnUrl);
    }

    public function getStamp()
    {
        return $this->getParameter('stamp');
    }

    public function setStamp($stamp)
    {
        $this->setParameter('stamp', $stamp);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($amount)
    {
        $this->setParameter('amount', $amount);
    }

    public function getReference()
    {
        return $this->getParameter('reference');
    }

    public function setReference($reference)
    {
        $this->setParameter('reference', $reference);
    }
}
