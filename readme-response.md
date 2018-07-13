# Prowl [![Latest Stable Version](https://poser.pugx.org/midnite81/prowl/version)](https://packagist.org/packages/midnite81/prowl) [![Total Downloads](https://poser.pugx.org/midnite81/prowl/downloads)](https://packagist.org/packages/midnite81/prowl) [![Latest Unstable Version](https://poser.pugx.org/midnite81/prowl/v/unstable)](https://packagist.org/packages/midnite81/prowl) [![License](https://poser.pugx.org/midnite81/prowl/license.svg)](https://packagist.org/packages/midnite81/prowl) [![Build](https://travis-ci.org/midnite81/prowl.svg?branch=master)](https://travis-ci.org/midnite81/prowl) [![Coverage Status](https://coveralls.io/repos/github/midnite81/prowl/badge.svg?branch=master)](https://coveralls.io/github/midnite81/prowl?branch=master)

## Response Object

When a push notification has been sent, if no exceptions have been thrown at the point of the push, you will receive
a `Response` object back. 

The response natively comes back in Xml, but the response object converts this and provides methods so you can easily
obtain the information you want. 

|Method                       |Description                                                        |
|-----------------------------|-------------------------------------------------------------------|
|isSuccess()                  |This identifies whether the request has been successful or not     |
|isError()                    |This identifies whether an error has occurred                      |
|toArray()                    |This returns the response as an array                              |
|toJson()                     |This returns the response as a json object                         |
|getResponse()                |Get the original Http Response                                     |
|getContents()                |Get the original Xml Response                                      |
|getFormattedXml()            |Get the formatted Xml                                              |
|getStatusCode()              |Get the status code returned from the API                          |
|getRemaining()               |Get the remaining number of calls you can make before it resets    |
|getResetDate($carbon = true) |Get the reset date (returned as Carbon object unless set to false  |
|getErrorCode()               |Get the error code returned from the API                           |
|getErrorMessage()            |Get the error message returned from the API                        |
|getRetrieveApiKey()*         |Get the retrieved api key                                          |
|getToken()*                  |Get the retrieved token                                            |

If you call a method but there is no information to return it will provide you a `null` response. 

You will only retrieve information to the starred methods if you have called an appropriate method on the api.

Error information will only be available if an error occurred on the API. You would be advised to do an if check
to play safe; 

```php
if ($response->isSuccess()) { 
   // do something
} else { 
  // do something else if failed
}
```


