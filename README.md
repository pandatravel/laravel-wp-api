# Laravel WP API
> A Wordpress REST API client for Laravel. 

## Installation
### Composer
Execute the following command to get the latest version of the package:

```bash
$ composer require ammonkc/laravel-wp-api
```

### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

In your `config/app.php` add `Ammonkc\WpApi\WpApiServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Ammonkc\WpApi\WpApiServiceProvider::class,
],
```

## Configuration
Publish Configuration

```shell
php artisan vendor:publish --provider "Ammonkc\WpApi\WpApiServiceProvider"
```

## Version
* 1.0.0

## Licence
This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Installing as a dependency on a laravel project
Is very likely you'll need to install this package locally to test the integration. You can do so by adding the follow to the `composer.json` file in your laravel project.

```json
    "repositories": [
        {
            "type": "path",
            "url": "path/to/package/folder"
        }
    ],
```

Then, in your laravel project root path you can just run:

```bash
$ composer require namespace/package-name
```

## Credits
This package leverages vnn/wordpress-rest-api-client with added enhancements for laravel
