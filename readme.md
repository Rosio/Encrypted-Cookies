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
use Rosio\EncryptedCookie\CookieFactory;
use Rosio\EncryptedCookie\CryptoSystem\AES_SHA1;

// Used to create cookies with a given cryptoSystem
$cookieFactory = new CookieFactory(new AES_SHA1('symmetricKey', 'HMACKey'));

$data = 'blah';

$factory->create('testCookie')->setData($data)->save();

$newCookie = $factory->get('testCookie');

echo $newCookie->getData();
```
