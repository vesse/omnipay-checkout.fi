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
class RefundResponse extends AbstractResponse implements ResponseInterface
{
    private $isSuccessful = false;
    private $statusCode;
    private $statusMessage;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if ($this->data instanceof \SimpleXMLElement) {
            if (isset($this->data->response)) {
                if (isset($this->data->response->statusCode)) {
                    $this->statusCode = (string) $this->data->response->statusCode;
                }
                if (isset($this->data->response->statusMessage)) {
                    $this->statusMessage = (string) $this->data->response->statusMessage;
                }

                if ($this->statusCode === '2100') {
                    $this->isSuccessful = true;
                }
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
     * {@inheritDoc}
     */
    public function isRedirect()
    {
        return false;
    }
}
