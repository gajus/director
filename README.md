# Director

[![Build Status](https://travis-ci.org/gajus/director.png?branch=master)](https://travis-ci.org/gajus/director)
[![Coverage Status](https://coveralls.io/repos/gajus/director/badge.png?branch=master)](https://coveralls.io/r/gajus/director?branch=master)

Utility for generating URLs relative to the predefined routes and for handling the redirects.

## Use case

Inject an instance of `Router` to your template and use it to generate links. Then if your URLs schema ever changes, you will be able to adjust it in the Router configuration (subject to that the resource path does not change). It is esp. convenient when your URL schema varies between the development environments.

## URLs

`Router` instance carries predefined routes that are used to construct URLs.

```php
// Set the default route:
$router = new \Gajus\Director\Router('http://gajus.com/');
// Set "static" route:
$router->setRoute('http://static.gajus.com/', 'static');

// Get the default route:
// http://gajus.com/
$router->url();

// Get "static" route:
// http://static.gajus.com/
$router->url(null, 'static');

// Get absolute URL for the default route:
// http://gajus.com/post/1
$router->url('post/1');

// Get absolute URL for the "static" route:
// http://static.gajus.com/css/frontend.css
$router->url('css/frontend.css', 'static');
```

### Redirect

```php
// Redirect to $_SERVER['HTTP_REFERER'] or default to $router->url():
$router->go();

// Redirect to the default path with status code 307:
$router->go( $router->url(), 307 );
```

> Redirect status code will default to 303 when current request is POST. 302 otherwise.


`go` will throw `Exception\LogicException` exception if [headers have been already sent](http://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php).

### Get path

The iverse of the `url` method is `getPath`. It is used to get the resource path of the current request URI relative to a specific route:

```php
// Taken from ./tests/RouterTest.php

$router = new \Gajus\Skip\Router('https://gajus.com/foo/');

$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_HOST'] = 'gajus.com';
$_SERVER['REQUEST_URI'] = '/foo/';

$this->assertSame('', $router->getPath());

$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_HOST'] = 'gajus.com';
$_SERVER['REQUEST_URI'] = '/foo/bar/';

$this->assertSame('bar/', $router->getPath());

$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_HOST'] = 'gajus.com';
$_SERVER['REQUEST_URI'] = '/foo/bar/?foo[bar]=1';

$this->assertSame('bar/', $router->getPath());
```

## Logging

Implements [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) `LoggerAwareInterface`.
