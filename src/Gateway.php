<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 *
 * Latest driver release:
 * https://github.com/TODO/
 *
 */
namespace Omnipay\CheckoutFi;

use Omnipay\Common\AbstractGateway;

/**
 * Checkout.fi Omnipay gateway
 */
class Gateway extends AbstractGateway
{
    private static $PAYMENT_URL = 'https://payment.checkout.fi/';

    public function getName()
    {
        return 'Checkout.fi';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'merchantSecret' => '',
            'returnUrl' => '',
            'testMode' => false
        );
    }

    public static function getPaymentUrl()
    {
        return Gateway::$PAYMENT_URL;
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    public function setMerchantSecret($merchantSecret)
    {
        return $this->setParameter('merchantSecret', $merchantSecret);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function setReturnUrl($returnUrl)
    {
        return $this->setParameter('returnUrl', $returnUrl);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\CompletePurchaseRequest', $parameters);
    }
}
