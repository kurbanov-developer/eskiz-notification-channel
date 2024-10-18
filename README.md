<div align="center">
  <img src="https://eskiz.uz/logo.svg" alt="Eskiz Logo">
</div>

# Eskiz Notifications Channel for Laravel

## Installation

To integrate the Eskiz notifications channel with your Laravel application, you can use Composer. Run the following command in your terminal:

```bash
composer require kurbanov-developer/eskiz-notification-channel
```
After successful installation, update your .env file with the following configuration:

``` dotenv
ESKIZ_EMAIL=your@email.com
ESKIZ_PASSWORD=your_secret_code_from_dashboard
```
Additionally, make changes in your services.php file to include Eskiz configuration:

Copy code
``` php
'eskiz' => [
    'email' => env('ESKIZ_EMAIL', ''),
    'password' => env('ESKIZ_PASSWORD', ''),
],
```

Official Documentation
Explore the detailed documentation at Eskiz Postman Documentation for comprehensive information.

## Usage
To utilize the Eskiz notification channel, add the following method to your User model:

Copy code
``` php
public function routeNotificationForEskiz()
{
    return $this->mobile;
}
```
Additionally, employ the provided example to send notifications using Eskiz in your notification class:

Copy code
``` php
public function toEskiz($notifiable)
{
    return (new EskizMessage())
        ->content("Your notification content goes here");
}
```
## About the Author
This Laravel package is developed and maintained by Kurbonov Ismoil.

Website: [kurbonov.ru](kurbonov.ru)

Feel free to contact the author for any inquiries or support related to this package.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email kurbanov.developer@yandex.ru instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.