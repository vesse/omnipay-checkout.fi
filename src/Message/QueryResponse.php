<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 */
namespace Omnipay\CheckoutFi\Message;

use Omnipay\CheckoutFi\Gateway;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Refund request for Checkout.fi
 */
class QueryResponse extends AbstractResponse implements ResponseInterface
{
    private $isSuccessful = false;
    private $statusCode;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (array_key_exists('xml', $this->data) && $this->data['xml'] instanceof \SimpleXMLElement) {
            if (isset($this->data['xml']->status)) {
                $this->statusCode = (string) $this->data['xml']->status;
                $this->isSuccessful = true;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return $this->isSuccessful;
    }

    /**
     * Get the status code from the received XMl
     *
     * @return string
     */
    public function getResponseStatusCode()
    {
        return $this->statusCode;
    }


    /**
     * Get the HTTP response status code
     *
     * @return int The HTTP status code
     */
    public function getStatusCode()
    {
        return $this->data['statusCode'];
    }
}
