# Prowl 
[![Latest Stable Version](https://poser.pugx.org/midnite81/prowl/version)](https://packagist.org/packages/midnite81/prowl) [![Total Downloads](https://poser.pugx.org/midnite81/prowl/downloads)](https://packagist.org/packages/midnite81/prowl) [![Latest Unstable Version](https://poser.pugx.org/midnite81/prowl/v/unstable)](https://packagist.org/packages/midnite81/prowl) [![License](https://poser.pugx.org/midnite81/prowl/license.svg)](https://packagist.org/packages/midnite81/prowl) [![Build](https://travis-ci.org/midnite81/prowl.svg?branch=master)](https://travis-ci.org/midnite81/prowl) [![Coverage Status](https://coveralls.io/repos/github/midnite81/prowl/badge.svg?branch=master)](https://coveralls.io/github/midnite81/prowl?branch=master)   
_A PHP wrapper for using the [Prowl API](https://www.prowlapp.com), with a Laravel 5 service provider for integration with Laravel_

# Installation

This package requires PHP 5.6+, and comes with a Laravel Service Provider and Facade for Laravel integration. 
Please note, you do not need to have laravel installed to use this package. 

To install through composer include the package in your `composer.json`.

    "midnite81/prowl": "^2.0.0"

Run `composer install` or `composer update` to download the dependencies or you can 
run `composer require midnite81/prowl`.

## Http Standards

To adhere to better standards, this package uses the popular and powerful PHP-HTTP library 
to make HTTP requests. By default a Guzzle adapter is required when using `midnite81\prowl`.
This allows you, should you wish, to use your own HTTP Client instead of Guzzle. For more 
information on PHP-HTTP, please visit [php-http.org](http://docs.php-http.org/)

## Versioning
|Version|Description|
|---|---|
|v3.*|Support added to use Carbon 1 or 2 as a dependency|
|v2.*|Completely rewritten from the ground up, with no additional prowl dependencies|
|v1.*|Unsupported - Relied on other prowl packages|

## Laravel 5 Integration

If you wish to use this package with Laravel, please visit the [laravel specific readme](readme-laravel.md). 

## Standard (Non laravel) implementation

To get started using this Prowl wrapper, you will need to instantiate the prowl class. The construct of the `Prowl` 
object takes an array for configuration. If you don't provide one, it will just use default values. In the example 
below I've provided a sample config. 

```php
<?php
use Midnite81\Prowl\Prowl;
include __DIR__ . '../vendor/autoload.php';

/**
 * By default, the package will use Guzzle to process our HTTP requests, but you can use anything that
 * implements the PHP-HTTP standard
 */

$prowl = Prowl::create(['apiUrl' => 'https://api.prowlapp.com/publicapi']); 

```

There are four main methods on the Prowl class, which identify the main four api calls you can make. 

|Api Method      |Prowl Method                                  |Description                                                          |
|----------------|----------------------------------------------|---------------------------------------------------------------------|
|add             | $prowl->add(Notification $notification)      | Sends a push notification to one or more devices                    |
|verify          | $prowl->verify($apiKey, $providerKey)        | Verifies the api key is valid                                       |
|retrieve/token  | $prowl->retrieveToken($providerKey)          | Get a registration token for use in retrieve/apikey                 |
|retrieve/apikey | $prowl->retrieveApiKey($providerKey, $token) | Get an API Key from a registration token received in retrieve/token |

 To understand what each of these api calls does, you should check out the documentation at 
 [https://www.prowlapp.com/api.php](https://www.prowlapp.com/api.php)
 
 In order to send a notification to a prowl linked device, you will need to create a `Notification` object. There are a 
 couple of ways in which to do this. You can either call instantiate a new `Notification` object or you can call it 
 from the Prowl object. If you call it from the Prowl object the prowl object will be sent to it so you can call the 
 send method via chaining. There are a couple of factory methods on the `Notification` object should you rather use those.
 
 ```php 
 <?php
 use Midnite81\Prowl\Services\Notification;
 $notification = new Notification(); 
 // or 
 $notification = $prowl->createNotification(); 
 
 // Using this method you can chain through to send the message directly.
 $prowl = new Prowl($config); 
 $prowl->createMessage()
     ->setApiKeys($apiKey)
     ->setDescription('This is the description')
     ->setEvent('This is the event')
     ->setPriority(\Midnite81\Prowl\Services\Priority::NORMAL)
     ->setMessage('This is the message')
     ->setApplication('Application')
     ->send();
 ```
 
Once the `notification` object is created you can call methods to add to the Object. For example; 

```php 
$notification->setApiKeys($myKey)
             ->setPriority(0)
             ->setEvent('The Event')
             ->setDescription('The Description')
             ->setApplication('The Application')
             ->setMessage('The Message');
```

Once the notification has all the parameters it needs you can pass it to the add method to trigger a push notification.

```php 
$pushNotification = $prowl->add($notification);
```

Unless there are any Exceptions thrown you will receive a `Response` object back. For more information on the response
object please view [readme-response.md](readme-response.md) 
