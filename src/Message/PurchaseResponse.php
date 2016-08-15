<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Purchase response message from checkout.fi
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return $this->data['isRedirect'];
    }

    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data['location'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getMessage()
    {
        $html = $this->data['body'];
        if (!strpos($html, "Maksutapahtuman luonti ei onnistunut")) {
            return null;
        }
        $strStart = 84;
        $strLength = strpos($html, '</p><p><a') - $strStart;
        return "Error in field: " . substr($html, $strStart, $strLength);
    }
}
