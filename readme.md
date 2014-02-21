Encrypted Cookie
================
Library provides basic functionality to easily create and manage encrypted cookies.

Based off of [RFC 6896 KoanLogic's Secure Cookie Sessions for HTTP](https://tools.ietf.org/html/rfc6896).

Quick Example
-------------

```php
// Used to create cookies with a given cryptoSystem
$cookieFactory = new CookieFactory(new AES_SHA1('symmetricKey', 'HMACKey'));

$data = 'blah';

$factory->create('testCookie')->setData($data)->save();

$newCookie = $factory->get('testCookie');

echo $newCookie->getData();
```