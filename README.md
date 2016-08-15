# omnipay-checkout.fi

Omnipay driver for Checkout.fi. The driver redirects the user to checkout.fi website for the actual payment, and once complete checkout.fi calls the provided `returnUrl`.

## Installation

## Basic Usage

The following gateways are provided by this package:

* CheckoutFi

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

See the [API documentation](http://www.checkout.fi/materiaalit/tekninen-materiaali/) (in Finnish) for the request parameters. Values for `VERSION`, `CURRENCY`, `DEVICE`, `CONTENT`, `TYPE`, and `ALGORITHM` are fixed already.

## Development

```bash
composer install
composer dump-autoload
composer test
```
