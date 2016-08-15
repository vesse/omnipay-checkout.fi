<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Guzzle\Http\Exception\BadResponseException;

/**
 * Purchase request message for Checkout.fi Omnipay driver
 */
class PurchaseRequest extends AbstractRequest
{
    private static $MAC_FIELDS = array(
        'VERSION', 'STAMP', 'AMOUNT', 'REFERENCE', 'MESSAGE',
        'LANGUAGE', 'MERCHANT', 'RETURN', 'CANCEL', 'REJECT',
        'DELAYED', 'COUNTRY', 'CURRENCY', 'DEVICE', 'CONTENT',
        'TYPE', 'ALGORITHM', 'DELIVERY_DATE', 'FIRSTNAME', 'FAMILYNAME',
        'ADDRESS', 'POSTCODE', 'POSTOFFICE');

    public function getData()
    {
        $data = array(
            'VERSION'       => '0001',
            'STAMP'         => $this->getStamp(),
            'AMOUNT'        => $this->getAmount(),
            'REFERENCE'     => $this->getReference(),
            'MESSAGE'       => $this->getMessage(),
            'LANGUAGE'      => $this->getLanguage(),
            'MERCHANT'      => $this->getMerchantId(),
            'RETURN'        => $this->getReturnUrl(),
            'CANCEL'        => $this->getReturnUrl(),
            'REJECT'        => $this->getReturnUrl(),
            'DELAYED'       => $this->getReturnUrl(),
            'COUNTRY'       => $this->getCountry(),
            'CURRENCY'      => 'EUR',
            'DEVICE'        => '1',
            'CONTENT'       => '1',
            'TYPE'          => '0',
            'ALGORITHM'     => '3',
            'DELIVERY_DATE' => $this->getDeliveryDate(),
            'FIRSTNAME'     => $this->getFirstName(),
            'FAMILYNAME'    => $this->getFamilyName(),
            'ADDRESS'       => $this->getAddress(),
            'POSTCODE'      => $this->getPostcode(),
            'POSTOFFICE'    => $this->getPostoffice(),
            'EMAIL'         => $this->getEmail(),
            'PHONE'         => $this->getPhone()
        );

        $hashString = join('+', array_map(function ($field) use (&$data) {
            return $data[$field];
        }, self::$MAC_FIELDS)) . '+' . $this->getMerchantSecret();
        $data['MAC'] = strtoupper(md5($hashString));
        return $data;
    }

    public function sendData($data)
    {
        $this->httpClient->setBaseUrl($this->getPaymentUrl());
        $request = $this->httpClient->post(null, null, $data);

        $httpResponse = $request->send();

        return new PurchaseResponse($this, $httpResponse);
    }

    public function getMessage()
    {
        return $this->getParameter('message');
    }

    public function setMessage($message)
    {
        return $this->setParameter('message', $message);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($language)
    {
        return $this->setParameter('language', $language);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($country)
    {
        return $this->setParameter('country', $country);
    }

    public function getDeliveryDate()
    {
        return $this->getParameter('deliveryDate');
    }

    public function setDeliveryDate($deliveryDate)
    {
        return $this->setParameter('deliveryDate', $deliveryDate);
    }

    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    public function setFirstName($firstName)
    {
        return $this->setParameter('firstName', $firstName);
    }

    public function getFamilyName()
    {
        return $this->getParameter('familyName');
    }

    public function setFamilyName($familyName)
    {
        return $this->setParameter('familyName', $familyName);
    }

    public function getAddress()
    {
        return $this->getParameter('address');
    }

    public function setAddress($address)
    {
        return $this->setParameter('address', $address);
    }

    public function getPostcode()
    {
        return $this->getParameter('postcode');
    }

    public function setPostcode($postcode)
    {
        return $this->setParameter('postcode', $postcode);
    }

    public function getPostoffice()
    {
        return $this->getParameter('postoffice');
    }

    public function setPostoffice($postoffice)
    {
        return $this->setParameter('postoffice', $postoffice);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($email)
    {
        return $this->setParameter('email', $email);
    }

    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    public function setPhone($phone)
    {
        return $this->setParameter('phone', $phone);
    }
}
