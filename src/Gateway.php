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

    private static $RESPONSE_MAC_FIELDS = array(
        'VERSION', 'STAMP', 'REFERENCE', 'PAYMENT', 'STATUS', 'ALGORITHM'
    );

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

    /**
     * Validate the MAC field from purchase response
     */
    public function validateResponseMac(array $parameters = array())
    {
        if (!self::allRequiredKeysExist($parameters)) {
            return false;
        }

        // Only SHA-256 supported
        if ($parameters['ALGORITHM'] !== '3' && $parameters['ALGORITHM'] !== 3) {
            return false;
        }

        $hashString = join(
            '&',
            array_map(function ($field) use (&$parameters) {
                return $parameters[$field];
            }, self::$RESPONSE_MAC_FIELDS)
        );

        $calculatedMac = strtoupper(hash_hmac('sha256', $hashString, $this->getMerchantSecret()));

        return $parameters['MAC'] === $calculatedMac;
    }

    private static function allRequiredKeysExist(array $parameters)
    {
        if (!array_key_exists('MAC', $parameters)) {
            return false;
        }

        foreach (self::$RESPONSE_MAC_FIELDS as $field) {
            if (!array_key_exists($field, $parameters)) {
                return false;
            }
        }

        return true;
    }
}
