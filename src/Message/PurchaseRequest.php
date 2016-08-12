<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

/**
 * Purchase request message for Checkout.fi Omnipay driver
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'VERSION'   = $this->getVersion(),
            'STAMP'     = $this->getStamp(),
            'AMOUNT'    = $this->getAmount(),
            'REFERENCE' = $this->getReference(),
            'MESSAGE'   = $this->getMessage(),
            'LANGUAGE'  = $this->getLanguage(),
            'MERCHANT'  = $this->getMerchant(),
            'RETURN'    = $this->getReturnUrl(),
            'CANCEL' = $this->getReturnUrl(),
            'REJECT' = $this->getReturnUrl(),
            'DELAYED' = $this->getReturnUrl(),
            'COUNTRY' = $this->getCountry(),
            'CURRENCY' = 'EUR',
            'DEVICE' = '1',
            'CONTENT' = '1',
            'TYPE' = '0',
            'ALGORITHM' = '3',
            'DELIVERY_DATE' = $this->getDeliveryDate(),
            'FIRSTNAME' = $this->getFirstName(),
            'FAMILYNAME' = $this->getFamilyName(),
            'ADDRESS' = $this->getAddress(),
            'POSTCODE' = $this->getPostcode(),
            'POSTOFFICE' = $this->getPostoffice(),
            'EMAIL' = $this->getEmail(),
            'PHONE' = $this->getPhone()
        );

    }

    public function sendData($data)
    {

    }
}
