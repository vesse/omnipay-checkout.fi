<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;


use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
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

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setVersion($version)
    {
        $this->setParameter('version', $version);
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

    public function getMessage()
    {
        return $this->getParameter('message');
    }

    public function setMessage($message)
    {
        $this->setParameter('message', $message);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($language)
    {
        $this->setParameter('language', $language);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($country)
    {
        $this->setParameter('country', $country);
    }

    public function getDeliveryDate()
    {
        return $this->getParameter('deliveryDate');
    }

    public function setDeliveryDate($deliveryDate)
    {
        $this->setParameter('deliveryDate', $deliveryDate);
    }

    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    public function setFirstName($firstName)
    {
        $this->setParameter('firstName', $firstName);
    }

    public function getFamilyName()
    {
        return $this->getParameter('familyName');
    }

    public function setFamilyName($familyName)
    {
        $this->setParameter('familyName', $familyName);
    }

    public function getAddress()
    {
        return $this->getParameter('address');
    }

    public function setAddress($address)
    {
        $this->setParameter('address', $address);
    }

    public function getPostcode()
    {
        return $this->getParameter('postcode');
    }

    public function setPostcode($postcode)
    {
        $this->setParameter('postcode', $postcode);
    }

    public function getPostoffice()
    {
        return $this->getParameter('postoffice');
    }

    public function setPostoffice($postoffice)
    {
        $this->setParameter('postoffice', $postoffice);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($email)
    {
        $this->setParameter('email', $email);
    }

    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    public function setPhone($phone)
    {
        $this->setParameter('phone', $phone);
    }

}
