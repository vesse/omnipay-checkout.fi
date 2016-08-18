<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use \Omnipay\CheckoutFi\Gateway;

/**
 * Refund request for Checkout.fi
 */
class RefundRequest extends AbstractAPIRequest
{
    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $this->validate('stamp', 'amount', 'reference', 'email');

        $doc = new \SimpleXMLElement('<checkout/>');

        $id = $doc->addChild('identification');
        $id->addChild('merchant', self::ensureLength($this->getMerchantId(), 20));
        $id->addChild('stamp', self::ensureLength($this->getStamp(), 20));

        $refund = $doc->addChild('message')->addChild('refund');
        $refund->addChild('stamp', self::ensureLength($this->getRefundStamp(), 20));
        $refund->addChild('reference', self::ensureLength($this->getReference(), 20));
        $refund->addChild('amount', self::ensureLength($this->getAmount(), 8));

        $receiver = $refund->addChild('receiver');
        $receiver->addChild('email', self::ensureLength($this->getEmail(), 200));

        $strPayload = base64_encode($doc->asXML());

        $data = array(
            'data' => $strPayload,
            'mac'  => strtoupper(hash_hmac("sha256", $strPayload, $this->getMerchantSecret()))
        );

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function sendData($data)
    {
        $this->httpClient->setBaseUrl(Gateway::getRefundUrl());
        $request = $this->httpClient->post(null, null, $data);

        $httpResponse = $request->send();

        // Response is always 200 OK with content type text/html, so just
        // try to load the body as XML and hope it works.
        $xml = simplexml_load_string($httpResponse->getBody(true));

        return $this->response = new RefundResponse($this, $xml);
    }


    /**
     * Get the stamp of the payment to be refunded
     *
     * @return string
     */
    public function getRefundStamp()
    {
        return $this->getParameter('refundStamp');
    }

    /**
     * Set the stamp of the payment to be refunded
     *
     * @param string $stamp The stamp of the original payment request
     */
    public function setRefundStamp($stamp)
    {
        $this->setParameter('refundStamp', self::toString($stamp));
    }


    /**
     * Get the recipient email address
     *
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the recipient email address
     *
     * @param string $email Email address of the refund recipient
     */
    public function setRecipientEmail($email)
    {
        $this->setParameter('email', $email);
    }
}
