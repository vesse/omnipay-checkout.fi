<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use \Omnipay\CheckoutFi\Gateway;

/**
 * Purchase request message for Checkout.fi Omnipay driver
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('stamp', 'amount', 'reference', 'deliveryDate');

        $data = array(
            'VERSION'       => '0001',
            'STAMP'         => $this->ensureLength($this->getStamp(), 20),
            'AMOUNT'        => $this->ensureLength($this->getAmount(), 8),
            'REFERENCE'     => $this->ensureLength($this->getReference(), 20),
            'MESSAGE'       => $this->ensureLength($this->getMessage(), 1000),
            'LANGUAGE'      => $this->ensureLength($this->getLanguage(), 2),
            'MERCHANT'      => $this->ensureLength($this->getMerchantId(), 20),
            'RETURN'        => $this->ensureLength($this->getReturnUrl(), 300),
            'CANCEL'        => $this->ensureLength($this->getReturnUrl(), 300),
            'REJECT'        => $this->ensureLength($this->getReturnUrl(), 300),
            'DELAYED'       => $this->ensureLength($this->getReturnUrl(), 300),
            'COUNTRY'       => $this->ensureLength($this->getCountry(), 3),
            'CURRENCY'      => 'EUR',
            'DEVICE'        => '1',
            'CONTENT'       => $this->ensureLength($this->getContent(), 2),
            'TYPE'          => '0',
            'ALGORITHM'     => '3',
            'DELIVERY_DATE' => $this->ensureLength($this->getDeliveryDate(), 8),
            'FIRSTNAME'     => $this->ensureLength($this->getFirstName(), 40),
            'FAMILYNAME'    => $this->ensureLength($this->getFamilyName(), 40),
            'ADDRESS'       => $this->ensureLength($this->getAddress(), 40),
            'POSTCODE'      => $this->ensureLength($this->getPostcode(), 14),
            'POSTOFFICE'    => $this->ensureLength($this->getPostoffice(), 18),
        );

        $hashString  = join('+', $data) . '+' . $this->getMerchantSecret();
        $data['MAC'] = strtoupper(md5($hashString));
        $data['EMAIL'] = $this->ensureLength($this->getEmail(), 200);
        $data['PHONE'] = $this->ensureLength($this->getPhone(), 30);
        return $data;
    }

    public function sendData($data)
    {
        $this->httpClient->setBaseUrl(Gateway::getPaymentUrl());
        $request = $this->httpClient->post(null, null, $data);
        $request->getParams()->set('redirect.disable', true);

        $httpResponse = $request->send();

        $responseData = array(
            'location'   => $this->buildRedirectUrl($httpResponse->getLocation()),
            'isRedirect' => $httpResponse->isRedirect(),
            'body'       => $httpResponse->getBody(true),
            'stamp'      => $data['STAMP'],
            'statusCode' => $httpResponse->getStatusCode()
        );

        return $this->response = new PurchaseResponse($this, $responseData);
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

    public function getContent()
    {
        return $this->getParameter('content') || '1';
    }

    public function setContent($content)
    {
        return $this->setParameter('content', $content);
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

    private function buildRedirectUrl($location)
    {
        return join('/', array(trim(Gateway::getPaymentUrl(), '/'), trim($location, '/')));
    }

    private function ensureLength($parameter, $length)
    {
        if (is_null($parameter)) {
            return null;
        }

        return mb_substr($parameter, 0, $length);
    }
}
