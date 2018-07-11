# Prowl [![Latest Stable Version](https://poser.pugx.org/midnite81/prowl/version)](https://packagist.org/packages/midnite81/prowl) [![Total Downloads](https://poser.pugx.org/midnite81/prowl/downloads)](https://packagist.org/packages/midnite81/prowl) [![Latest Unstable Version](https://poser.pugx.org/midnite81/prowl/v/unstable)](https://packagist.org/packages/midnite81/prowl) [![License](https://poser.pugx.org/midnite81/prowl/license.svg)](https://packagist.org/packages/midnite81/prowl)

# Laravel 5 Integration

To integrate this package more fully with Laravel 5, add the Prowl service provider to the list of service providers 
in `app/config/app.php`.

    'providers' => [

      Midnite81\Prowl\ProwlServiceProvider::class
              
    ];
    
Add the `Prowl` facade to your aliases array.

    'aliases' => [

      'Prowl' => Midnite81\Prowl\Facades\Prowl::class,
      
    ];
    
Publish the Prowl config file using 
`php artisan vendor:publish --provider="Midnite81\Prowl\ProwlServiceProvider"`
    
## Configuration File

Once you have published the config files, you will find a `prowl.php` file in the `config` folder. You should 
look through these settings and update these where necessary. 

You shouldn't need to update the api url, but it's available in the configuration should you need to.

## Methods 

There are four main methods on the Prowl class, which identify the main four api calls you can make. 

|Api Method      |Prowl Method                                  |Description                                                          |
|----------------|----------------------------------------------|---------------------------------------------------------------------|
|add             | $prowl->add(Notification $notification)      | Sends a push notification to one or more devices                    |
|verify          | $prowl->verify($apiKey, $providerKey)        | Verifies the api key is valid                                       |
|retrieve/token  | $prowl->retrieveToken($providerKey)          | Get a registration token for use in retrieve/apikey                 |
|retrieve/apikey | $prowl->retrieveApiKey($providerKey, $token) | Get an API Key from a registration token received in retrieve/token |

 To understand what each of these api calls does, you should check out the documentation at 
 [https://www.prowlapp.com/api.php](https://www.prowlapp.com/api.php)

## Example Usage

The laravel integration is not too dissimilar from the standard implementation but it takes care of all of the 
configuration issues as those are stored in the `config/prowl.php` file. 

You can use the Facade if you want to, but in the examples I provide I'll inject them as it's a little more efficient 
to do it that way.

```php
<?php 
class MyClass
{
    /**
     * Option one - send through notification method
     */
    public function optionOne(Midnite81\Prowl\Contracts\Prowl $prowl) { 
         $msg = $prowl->createMessage()
                     ->setApiKeys('iphone') // as defined in the 'keys' section of the prowl config
                     ->setPriority(Priority::HIGH) // or you can specify a number between -2 to 2
                     ->setEvent('The Event')
                     ->setDescription('The Description')
                     ->setApplication('The Application')
                     ->setMessage('The Message')
                     ->send();
   }
    
    /**
     * Option two create the message and then send through prowl
     */
    public function optionTwo(Midnite81\Prowl\Contracts\Prowl $prowl) { 
         $msg = $prowl->createMessage();
         $msg->setApiKeys('iphone') // as defined in the 'keys' section of the prowl config
             ->setPriority(Priority::HIGH) // or you can specify a number between -2 to 2
             ->setEvent('The Event')
             ->setDescription('The Description')
             ->setApplication('The Application')
             ->setMessage('The Message');
             
         $pushNotification = $prowl->add($msg);   
   }
}
```

The `setApiKeys` method can take either an array of api keys (or config'ed aliases) or a single string. You can call the
method any number of times you want and it will continue to add api keys to the `Notification` object.

For your convenience, the add method has been aliased to include `send` and `push`; therefore you can just as easily 
call `$prowl->push($msg);` or `$prowl->send($msg);`

## Response 

Unless there are any Exceptions thrown you will receive a `Response` object back. For more information on the response
object please view [readme-response.md](readme-response.md) 
