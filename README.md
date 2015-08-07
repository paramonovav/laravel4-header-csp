# Response header Content Security Policy for Laravel 4
Provides support for enforcing Content Security Policy and XSS Protection with headers in Laravel 4 responses.

*Note*: Based on [Content Security Policy](http://content-security-policy.com/), [Improving Web Security with the Content Security Policy](http://www.sitepoint.com/improving-web-security-with-the-content-security-policy/), [HTTP headers](https://www.owasp.org/index.php/List_of_useful_HTTP_headers).

## Key Features

1. Add rules for Content Security Policy (content-security-policy, x-content-security-policy, x-webkit-csp)
2. Save reports of policy failures to ```storage/logs/content-security-policy-report``` folder if needed
3. Add additional header like: ```x-xss-protection, x-frame-options, x-content-type-options```

## Installation

Require this package with composer:

```
composer require paramonovav/laravel4-header-csp
```

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

```
'Paramonovav\Laravel4HeaderCsp\Laravel4HeaderCspServiceProvider',
```

You need to publish the config from this package.

```
php artisan config:publish paramonovav/laravel4-header-csp
```
## Usage

### Apply content security policy to routes

The following will apply all default profiles to the ```login``` route.

```
Route::get('login', array('after'=>'response.secure'), function()
{
    return 'Hello, on login page !';
}));
```

The following will apply all default profiles and a specific ```google``` profile to the ```login``` route.

```
Route::get('login', array('after'=>'response.secure:google'), function()
{
    return 'Hello, on login page !';
}));
```

You can include any number of specific profiles. The following will apply default, google, flickr, and my_custom profiles to the ```login``` route.

```
Route::get('login', array('after'=>'response.secure:google-flickr-my_custom'), function()
{
    return 'Hello, on login page !';
}));
```
