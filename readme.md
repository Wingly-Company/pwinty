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
You need to configure your Pwinty api key and merchant id in your `.env` file. 

```
PWINTY_APIKEY=your_pwinty_key
PWINTY_MERCHANT=your_pwinty_merchant_id
```
You should also set the api environment to be either "sandbox" or "production"

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

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details.

## Credits

- [Dimitris Karapanos](https://github.com/gpanos)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [license file](license.md) for more information.
