Encrypted Cookie
================
Library provides basic functionality to easily create and manage encrypted cookies.

Based off of [RFC 6896 KoanLogic's Secure Cookie Sessions for HTTP](https://tools.ietf.org/html/rfc6896).

Testing
-------
To run unit tests:

1. `composer install`
2. `vendor/bin/phpunit`

Quick Example
-------------

```php
use Rosio\EncryptedCookie\CookieStorage;
use Rosio\EncryptedCookie\Cookie;
use Rosio\EncryptedCookie\CryptoSystem\AES_SHA;

// Used to create cookies with a given cryptoSystem
$storage = new CookieStorage(new AES_SHA('32charactercryptokeymustbe32chrs', 'HMACKey'));

// Create a cookie
$data = 'blah';
$cookie = Cookie::create('testCookie', $data);
$storage->save($cookie);

// Load the cookie
$newCookie = $storage->load('testCookie'); // Returns a PartialCookie
echo $newCookie->getData();
```
