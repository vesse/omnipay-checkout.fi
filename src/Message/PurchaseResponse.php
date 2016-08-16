<?php
/*
 * Checkout.fi driver for Omnipay PHP payment processing library
 * https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use \Omnipay\CheckoutFi\Gateway;

/**
 * Purchase response message from checkout.fi
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->data = array(
            'location'   => $this->buildRedirectUrl($data->getLocation()),
            'isRedirect' => $data->isRedirect(),
            'body'       => $data->getBody()
        );
    }

    public function isSuccessful()
    {
        // RedirectResponse always returns false
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
        $matches = array();
        $match = preg_match('/kentässä: (.*?)</', $html, $matches);
        if ($match === 0) {
            return null;
        }
        if ($match === 1) {
            return 'Error in field: ' . $matches[1];
        }
        return 'Error in transaction';
    }

    private function buildRedirectUrl($location)
    {
        return join('/', array(trim(Gateway::getPaymentUrl(), '/'), trim($location, '/')));
    }
}
