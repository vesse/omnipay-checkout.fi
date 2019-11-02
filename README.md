# omnipay-checkout.fi

**Obsolete** This driver uses Checkout Finland legacy API and should not be used

**[Checkout.fi](http://www.checkout.fi/) driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/vesse/omnipay-checkout.fi.png?branch=master)](https://travis-ci.org/vesse/omnipay-checkout.fi)

Omnipay is a framework agnostic, multi-gateway payment processing library for PHP 5.3+. This package implements Checkout.fi support for Omnipay.

## Installation

**TODO**

## Basic Usage

The following gateways are provided by this package:

* CheckoutFi

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay) repository.

## Example

```php
$gateway = Omnipay::create('CheckoutFi')

$gateway.initialize(
    array(
        'merchantId' => '375917',
        'merchantSecret' => 'SAIPPUAKAUPPIAS',
        'returnUrl' => 'https://my.ecommerce.example.com/return'
    )
);

$response = $gateway->purchase(
    array(
        'stamp' => 'YourUniqueIdentifier',
        'amount' => '1200', // Amount in cents
        'reference' => 'YourReference',
        'deliveryDate' => '20160815', // Estimated delivery date
        'firstName' => 'Paying',
        'familyName' => 'Customer',
        'address' => 'Streetaddress 123',
        'postCode' => '33100',
        'postOffice' => 'Tampere'
    )
)->send();

if ($response->isRedirect()) {
    // Redirect to checkout.fi site
    $response->redirect();
} else {
    // Request failed
    echo $response->getMessage();
}
```

See the [checkout.fi API documentation](http://www.checkout.fi/materiaalit/tekninen-materiaali/) (in Finnish) for the request parameters. Values for `VERSION`, `CURRENCY`, `DEVICE`, `CONTENT`, `TYPE`, and `ALGORITHM` are set already, although you may need to provide another value for `CONTENT`.

Once the purchase is completed or cancelled, checkout.fi will call you `returnUrl` with parameters defined in the API documentation. This you should handle with `completePurchase`, eg.

```php
// CompletePurchaseRequest will read the parameters from query string
$response = $gateway->completePurchase()->send();

if ($response->isSuccessful()) {
    // TODO
} else {
    // TODO
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on [Stack Overflow](http://stackoverflow.com/). Be sure to add the [omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/vesse/omnipay-checkout.fi/issues), or better yet, fork the library and submit a pull request.

## Development

```bash
composer install
composer dump-autoload
composer test
```
