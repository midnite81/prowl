<?php
use Midnite81\Prowl\Prowl;

include __DIR__ . '../vendor/autoload.php';

/**
 * By default, the package will use Guzzle to process our HTTP requests, but you can use anything that
 * implements the PHP-HTTP standard
 */

$prowl = Prowl::create(['apiUrl' => 'https://api.prowlapp.com/publicapi']);

// you do not have to pass a config through to prowl, however if you don't you will be using the default
// configuration. Currently the only configurable item you need to pass through is the api's url under the key
// 'apiUrl'. That's it - now you have your prowl instance.
