<?php
/**
 * Checkout.fi driver for Omnipay PHP payment processing library.
 *
 * @link https://www.checkout.fi/
 *
 * Latest driver release: @link https://github.com/TODO/
 */
namespace Omnipay\CheckoutFi;

use Omnipay\Common\AbstractGateway;

/**
 * Checkout.fi Omnipay gateway
 */
class Gateway extends AbstractGateway
{
    private static $PAYMENT_URL = 'https://payment.checkout.fi/';

    private static $REFUND_URL = 'https://rpcapi.checkout.fi/refund2';

    private static $QUERY_URL = 'https://rpcapi.checkout.fi/poll';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Checkout.fi';
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'merchantSecret' => '',
            'returnUrl' => '',
            'testMode' => false
        );
    }

    /**
     * Get the payment endpoint URL
     *
     * @return string
     */
    public static function getPaymentUrl()
    {
        return Gateway::$PAYMENT_URL;
    }

    /**
     * Get the refund endpoint URL
     *
     * @return string
     */
    public static function getRefundUrl()
    {
        return Gateway::$REFUND_URL;
    }

    /**
     * Get the query endpoint URL
     *
     * @return string
     */
    public static function getQueryUrl()
    {
        return Gateway::$QUERY_URL;
    }


    /**
     * Get the merchant ID
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set the merchant ID
     *
     * @param string $merchantId Merchant ID
     */
    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    /**
     * Get the merchant secret
     *
     * @return string
     */
    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    /**
     * Set the merchant secret
     *
     * @param string $merchantSecret Merchant secret
     */
    public function setMerchantSecret($merchantSecret)
    {
        return $this->setParameter('merchantSecret', $merchantSecret);
    }

    /**
     * Get the return URL
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
     * @param string $returnUrl Return URL
     */
    public function setReturnUrl($returnUrl)
    {
        return $this->setParameter('returnUrl', $returnUrl);
    }

    /**
     * {@inheritDoc}
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\PurchaseRequest', $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\RefundRequest', $parameters);
    }

    /**
     * Query payment status
     *
     * This is an extension to the Omnipay gateway interface, but could be useful for services
     * using only checkout.fi
     *
     * @param array $parameters HTTP request parameters
     * @return \Omnipay\CheckoutFi\Message\QueryRequest
     */
    public function query(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CheckoutFi\Message\QueryRequest', $parameters);
    }
}
