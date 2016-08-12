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
    private static $MAC_FIELDS = array(
        'VERSION', 'STAMP', 'AMOUNT', 'REFERENCE', 'MESSAGE',
        'LANGUAGE', 'MERCHANT', 'RETURN', 'CANCEL', 'REJECT',
        'DELAYED', 'COUNTRY', 'CURRENCY', 'DEVICE', 'CONTENT',
        'TYPE', 'ALGORITHM', 'DELIVERY_DATE', 'FIRSTNAME', 'FAMILYNAME',
        'ADDRESS', 'POSTCODE', 'POSTOFFICE');

    public function getData()
    {
        $data = array(
            'VERSION'       => $this->getVersion(),
            'STAMP'         => $this->getStamp(),
            'AMOUNT'        => $this->getAmount(),
            'REFERENCE'     => $this->getReference(),
            'MESSAGE'       => $this->getMessage(),
            'LANGUAGE'      => $this->getLanguage(),
            'MERCHANT'      => $this->getMerchant(),
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

    }
}
