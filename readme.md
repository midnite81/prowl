# Prowl [![Latest Stable Version](https://poser.pugx.org/midnite81/prowl/version)](https://packagist.org/packages/midnite81/prowl) [![Total Downloads](https://poser.pugx.org/midnite81/prowl/downloads)](https://packagist.org/packages/midnite81/prowl) [![Latest Unstable Version](https://poser.pugx.org/midnite81/prowl/v/unstable)](https://packagist.org/packages/midnite81/prowl) [![License](https://poser.pugx.org/midnite81/prowl/license.svg)](https://packagist.org/packages/midnite81/prowl)

# Http Standards

To adhere to better standards, this package uses the popular and powerful PHP-HTTP library 
to make HTTP requests. By default a Guzzle adapter is required when using `midnite81\prowl`.
This allows you, should you wish, to use your own HTTP Client instead of Guzzle. For more 
information on PHP-HTTP, please visit [php-http.org](http://docs.php-http.org/)

#Installation

This package requires PHP 5.6+, and includes a Laravel 5 Service Provider and Facade.

To install through composer include the package in your `composer.json`.

    "midnite81/prowl": "^1.0.0"

Run `composer install` or `composer update` to download the dependencies or you can 
run `composer require midnite81/prowl`.

##Laravel 5 Integration

To use the package with Laravel 5 firstly add the Prowl service provider to the list of service providers 
in `app/config/app.php`.

    'providers' => [

      Midnite81\Prowl\ProwlServiceProvider::class
              
    ];
    
Add the `Prowl` facade to your aliases array.

    'aliases' => [

      'Prowl' => Midnite81\Prowl\Facades\Prowl::class,
      
    ];
    
Publish the config files using 
`php artisan vendor:publish --provider="Midnite81\Prowl\ProwlServiceProvider"`

To access Prowl you can either use the Facade or the Messaging instance is bound to the IOC container and you can 
then dependency inject it via its contract.


    Prowl::get('foo');
    
    public function __construct(Midnite81\Prowl\Contracts\ProwlNotifier $prowl)
    {
        $this->prowl = $prowl;
    }
    
#Configuration File

Once you have published the config files, you will find a `prowl.php` file in the `config` folder. You should 
look through these settings and update these where necessary. 

# Example Usage

    use Midnite81\Prowl\Contracts\Services\ProwlNotifier;
    
    public function sendMessage(ProwlNotifier $prowl) 
    {
         $prowl->setSubject('Subject');
         $prowl->setPriority(2);
         $prowl->setMessage('Hello');
         $response = $prowl->send();
         
         // should you wish to send a different message in the same call, 
         // $prowl->clear() 
         
         $prowl->clear(); 
         
         $prowl->setSubject('New Mail');
         $prowl->setPriority(0);
         $prowl->device('iphone') 
         $prowl->setMessage('You have received new mail');
         $response = $prowl->send();
         
    }

# Setting the device/api

You can either specify the actual api key `$prowl->device('39hf298h39f8h23....')` or by using a predefined key from the 
config file `$prowl->device('iphone')`. 