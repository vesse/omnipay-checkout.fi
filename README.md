# omnipay-checkout.fi

Omnipay driver for [Checkout.fi](http://www.checkout.fi/).

The driver redirects the user to checkout.fi website for the payment, and once complete checkout.fi calls the provided `returnUrl`.

## Installation

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
        'STAMP' => 'YourUniqueIdentifier',
        'AMOUNT' => '1200', // Amount in cents
        'REFERENCE' => 'YourReference',
        'DELIVERY_DATE' => '20160815', // Estimated delivery date
        'FIRSTNAME' => 'Paying',
        'FAMILYNAME' => 'Customer',
        'ADDRESS' => 'Streetaddress 123',
        'POSTCODE' => '33100',
        'POSTOFFICE' => 'Tampere'
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

Once the purchase is completed or cancelled, checkout.fi will call you `returnUrl` with parameters defined in the API documentation. The gateway instance provides a method for validating the MAC field of the response:

```php
$gateway->validateResponseMac($queryStringParameters);
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
