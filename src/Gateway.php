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

    public function getName()
    {
        return 'Checkout.fi';
    }

    public function getDefaultParameters()
    {
        // TODO: Rest of the parameters
        return array(
            'merchantId' => '',
            'hashKey' => '',
            'device' => '1',
            'type' => '0',
            'algorithm' => '3',
            'testMode' => false
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchantId', $merchantId);
    }

    public function getDevice()
    {
        return $this->getParameter('device');
    }

    public function setDevice($device)
    {
        $this->setParameter('device', $device);
    }

    public function getType()
    {
        return $this->getParameter('type');
    }

    public function setType($type)
    {
        $this->setParameter('type', $type);
    }

    public function getAlgorithm()
    {
        return $this->getParameter('algorithm');
    }

    public function setAlgorithm($algorithm)
    {
        $this->setParameter('algorithm', $algorithm);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\PurchaseRequest', $parameters);
    }
}
