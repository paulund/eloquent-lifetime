# Eloquent Lifetime

[![Latest Version on Packagist](https://img.shields.io/packagist/v/paulund/eloquent-lifetime.svg?style=flat-square)](https://packagist.org/packages/paulund/eloquent-lifetime)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/paulund/eloquent-lifetime/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/paulund/eloquent-lifetime/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/paulund/eloquent-lifetime/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/paulund/eloquent-lifetime/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/paulund/eloquent-lifetime.svg?style=flat-square)](https://packagist.org/packages/paulund/eloquent-lifetime)

---
![Eloquent Lifetime](https://paulund.co.uk/images/eloquent-lifetime/eloquent-lifetime.webp)

This is a Laravel package that allows you to set a lifetime on an Eloquent model. 

The package includes a trait that you can add to your model to set a lifetime on the model.

There is also a command that will run in the background to automatically delete the model after the lifetime has expired.

## Installation
Install the package via composer:

```bash
composer require paulund/eloquent-lifetime
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Paulund\EloquentLifetime\EloquentLifetimeServiceProvider"
```

## Usage

To use this package you need to add the `EloquentLifetime` trait to your model.

```php
use Paulund\EloquentLifetime\EloquentLifetime;

class Post extends Model
{
    use EloquentLifetime;
}
```

This will then need you to add a lifetime method to your model which will return the carbon object to check the
`created_at` date against.

```php
public function lifetime(): Carbon
{
    return now()->subDays(10);
}
```

The above will then delete any model that has been created over 10 days ago.

### Manually run command

By default the scheduled command is turned off and therefore you will need to manually run the artisan command to delete the models.

```bash
php artisan model:lifetime
```

### Scheduled command

If you will like the command to run automatically you can add the following env to your application.

```bash
ELOQUENT_LIFETIME_SCHEDULED_COMMAND_ENABLED=true
```

By default this will run at midnight everyday but you can change this by adding the following to your env file.

```bash
ELOQUENT_LIFETIME_SCHEDULE=hourly, daily, weekly
```

### Models folder

By default the package will look in the `app/Models` folder for the models to delete. If you have your models in a different folder you can change this by adding the following to your env file.

```bash
ELOQUENT_LIFETIME_MODELS_FOLDER=app/somewhere/Models
```

## Testing
```bash
vendor/bin/testbench workbench:install
composer test
```

## Credits

- [paulund](https://paulund.co.uk)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
