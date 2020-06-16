# Pwinty

[![Latest Stable Version](https://poser.pugx.org/wingly/pwinty/v)](//packagist.org/packages/wingly/pwinty)
[![License](https://poser.pugx.org/wingly/pwinty/license)](//packagist.org/packages/wingly/pwinty)
[![StyleCI](https://styleci.io/repos/272447992/shield)](https://styleci.io/repos/272447992)
[![Total Downloads](https://poser.pugx.org/wingly/pwinty/downloads)](//packagist.org/packages/wingly/pwinty)

This package makes working with [Pwinty](https://pwinty.com) image-printing API in Laravel applications a breeze. You can place orders, add images and submit them for shipping. You can also subscribe to Pwinty webhooks after you configure your callback URL at the Pwinty dashboard.


## Installation

You can install this package through composer.

``` bash
composer require wingly/pwinty
```

## Usage

### Database migrations

The Pwinty service provider registers it's own database migrations, so make sure that you run your migrations after installing the package. A new orders table will be created to hold all your users orders.

``` bash
php artisan migrate
```

### Pwinty environment setup

You need to configure your Pwinty API key and merchant ID in your `.env` file. 

```
PWINTY_APIKEY=your_pwinty_key
PWINTY_MERCHANT=your_pwinty_merchant_id
```
You should also set the API environment to be either "sandbox" or "production"

```
PWINTY_API=sandbox
```

### Working with orders

Add the Orderer trait to your model. The trait provides methods to create and retrieve orders easily.

```php
use Wingly\Pwinty\Orderer;

class User extends Authenticatable
{
    use Orderer;
}
```

By default the `App\User` model is used. You can change this by specifying a different model in your `.env` file. 

```
PWINTY_MODEL=App\User
```

#### Creating orders

To create a new order first retrieve an instance of your orderer model and use the `newOrder` method to create an order. This method will return you an instance of the `OrderBuilder` where you can set your order parameters. You should finish the order by calling the `create` method last. Check the [Pwinty documentation](https://pwinty.com/api/) for all available parameters.

```php
$user = User::first();

$user->newOrder()
    ->setRecipientName('John Doe')
    ->setCountryCode('FR')
    ->create();
```

#### Adding images to orders

After creating an order you can add images to the order by calling the `addImage` method on your `Order` instance. The method requires an identification code of the product for this image and the image's URL. You can add multiple images to the order by chaining the `addImage` method.

```php
$order = Order::first();

$order->addImage(
    'ART-PRI-HPG-20X28-PRODIGI_GB', 
    'https://testserver.com/aphoto.jpg'
);
```

#### Submitting an order

When you are ready you can submit your order to Pwinty for processing. The validity of the order will first be checked and then submitted. If the order is not ready to be submitted an `OrderUpdateFailure` exception will be thrown. You can check if an order is submitted by calling the `submitted` method on your `Order` instance.

```php 
$order = Order::first();

$order->submit();

$order->fresh()->submitted(); // true
```

#### Cancelling an order

You can cancel an open order at any given time by calling the `cancel` method on your `Order` instance. If the order status is not cancellable an `OrderUpdateFailure` exception will be thrown. You can check if an order is cancelled by calling the `cancelled` method. 

```php 
$order = Order::first();

$order->cancel();

$order->fresh()->cancelled(); // true
```

#### Getting the raw Pwinty order

You can get the raw Pwinty order object by calling the `asPwintyOrder` method on your `Order` instance. Check the [Pwinty documentation](https://pwinty.com/api/) for an example response. 

```php
$order = Order::first();

$pwintyOrder = $order->asPwintyOrder();
```

### Processing Pwinty Webhooks

Pwinty can make callbacks to a custom URL whenever the status of one of your orders changes. By default, a route that points to a webhook controller is configured through the Pwinty service provider. All incoming Pwinty webhook requests will be handled there. 
Make sure that you have set up your callback URL under the integrations section of the Pwinty dashboard. The webhook controller listens to the `pwinty/webhook` URL path. 

#### Signed Webhook URL 
To secure your webhooks you must add a signed URL to Pwinty dashboard. For convenience the package contains a console command that will generate a secure URL for you. Copy the signed URL and add it to Pwinty dashboard. A middleware is in place to validate the signed route requests. 

```bash
php artisan pwinty:sign
```

#### CSRF Protection

You gonna need to list the URI as an exception to the `VerifyCsrfToken` middleware included in your application. 

```php 
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'pwinty/*'
    ];
}
```

#### Events

The package emits a `Wingly\Pwinty\Events\WebhookProcessed` event when a webhook was processed. The event contains the full payload of the Pwinty webhook.
You can listen to this event if your application requires to take any actions when a webhook is received. 

## Changelog

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details.

## Credits

- [Dimitris Karapanos](https://github.com/gpanos)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [license file](license.md) for more information.
