<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use \Omnipay\CheckoutFi\Gateway;

/**
 * Purchase request message for Checkout.fi Omnipay driver.
 *
 * Note: The setters for each field do not validate the value. Values are truncated
 * to their maximum lengths in getData function.
 */
class PurchaseRequest extends AbstractAPIRequest
{
    /**
     * {@inheritDoc}
     *
     * Note: All parameters are truncated to their respective maximum lengths.
     */
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
            'COUNTRY'       => $this->ensureLength($this->getCountry(), 3), // For real?
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

    /**
     * {@inheritDoc}
     */
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

    /**
     * Get the configured return URL value
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * Set the return URL
     *
     * @param string $returnUrl The URL to return to from checkout.fi
     */
    public function setReturnUrl($returnUrl)
    {
        $this->setParameter('returnUrl', $returnUrl);
    }


    /**
     * Get the to message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getParameter('message');
    }

    /**
     * Set the message
     *
     * @param string $message Message field value
     */
    public function setMessage($message)
    {
        return $this->setParameter('message', $message);
    }

    /**
     * Get the to language code
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Set the language code
     *
     * @param string $language Language ISO code (2 characteds)
     */
    public function setLanguage($language)
    {
        return $this->setParameter('language', $language);
    }

    /**
     * Get the to country code
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Set the country code
     *
     * @param string $country Country code
     */
    public function setCountry($country)
    {
        return $this->setParameter('country', $country);
    }

    /**
     * Get the to content type code
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getParameter('content') || '1';
    }

    /**
     * Set the content type code
     *
     * @param mixed $content Content code (1 or 2)
     */
    public function setContent($content)
    {
        return $this->setParameter('content', self::toString($content));
    }

    /**
     * Get the to estimated delivery date
     *
     * @return string
     */
    public function getDeliveryDate()
    {
        return $this->getParameter('deliveryDate');
    }

    /**
     * Set the estimated delivery date
     *
     * @param string $deliveryDate Date in format YYYYMMDD
     */
    public function setDeliveryDate($deliveryDate)
    {
        return $this->setParameter('deliveryDate', $deliveryDate);
    }

    /**
     * Get the to customer first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    /**
     * Set the customer first name
     *
     * @param string $firstName Customer first name
     */
    public function setFirstName($firstName)
    {
        return $this->setParameter('firstName', $firstName);
    }

    /**
     * Get the to customer family name
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->getParameter('familyName');
    }

    /**
     * Set the customer family name
     *
     * @param string $familyName Customer family name
     */
    public function setFamilyName($familyName)
    {
        return $this->setParameter('familyName', $familyName);
    }

    /**
     * Get the to customer street address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getParameter('address');
    }

    /**
     * Set the customer street address
     *
     * @param string $address Customer street address
     */
    public function setAddress($address)
    {
        return $this->setParameter('address', $address);
    }

    /**
     * Get the to customer post code
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('postcode');
    }

    /**
     * Set the customer post code
     *
     * @param string $postCode Customer post code
     */
    public function setPostcode($postCode)
    {
        return $this->setParameter('postcode', $postCode);
    }

    /**
     * Get the to customer post office
     *
     * @return string
     */
    public function getPostoffice()
    {
        return $this->getParameter('postoffice');
    }

    /**
     * Set the customer post office
     *
     * @param string $postOffice Customer post office
     */
    public function setPostoffice($postOffice)
    {
        return $this->setParameter('postoffice', $postOffice);
    }

    /**
     * Get the to customer email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the customer email address
     *
     * @param string
     */
    public function setEmail($email)
    {
        return $this->setParameter('email', $email);
    }

    /**
     * Get the to customer phone number
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    /**
     * Set the customer phone number
     *
     * @param string
     */
    public function setPhone($phone)
    {
        return $this->setParameter('phone', $phone);
    }

    /**
     * Construt the redirect URL without repeated slashes from base payment URL and the location header.
     *
     * @param string $location The location header value
     * @return string
     */
    private function buildRedirectUrl($location)
    {
        return join('/', array(trim(Gateway::getPaymentUrl(), '/'), trim($location, '/')));
    }

    /**
     * Truncate the given string to given length unless without converting null values to empty strings
     *
     * @param string $parameter The parameter to truncate
     * @param int $length The maximum length of the string
     * @return string
     */
    private function ensureLength($parameter, $length)
    {
        if (is_null($parameter)) {
            return null;
        }

        return mb_substr($parameter, 0, $length);
    }
}
