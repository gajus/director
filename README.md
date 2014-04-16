# Director

[![Build Status](https://travis-ci.org/gajus/director.png?branch=master)](https://travis-ci.org/gajus/director)
[![Coverage Status](https://coveralls.io/repos/gajus/director/badge.png?branch=master)](https://coveralls.io/r/gajus/director?branch=master)

Utility for generating URLs relative to the predefined routes and for handling the redirects.

## Use case

Use an instance of `Router` to generate URLs. It is convenient when your URL schema varies between the deployment environments.

## URLs

`Router` instance carries predefined routes that are used to construct URLs.

```php
/**
 * @param string $url Default route URL.
 */
$router = new \Gajus\Director\Router('http://gajus.com/');

/**
 * @todo Check if query string is included.
 * @param string $route_name Route name.
 * @param string $url Absolute URL.
 * @return null
 */
$router->setRoute('http://static.gajus.com/', 'static');
# null

/**
 * Get absolute URL using either of the predefined routes.
 * Requested resource path is appended to the route.
 *
 * @param string $path Relavite path to the route.
 * @param string $route Route name.
 */
$router->url();
# http://gajus.com/

// Get URL relative to the default route:
$router->url('post/1');
# http://gajus.com/post/1

// Get URL for the "static" route:
$router->url(null, 'static');
# http://static.gajus.com/

// Get URL relative to the "static" route:
$router->url('css/frontend.css', 'static');
# http://static.gajus.com/css/frontend.css
```

### Redirect

```php
/**
 * Redirect user agent to the given URL.
 *
 * If no $url is provided, then attempt to redirect to the referrer
 * or (when referrer is not available) to the default route.
 * 
 * @see http://benramsey.com/blog/2008/07/http-status-redirection/
 * @param string|null $url Absolute URL
 * @param int|null $response_code HTTP response code. Defaults to 303 when request method is POST, 302 otherwise.
 * @return null
 */
$router->location();
# null (script execution terminated)

// Redirect to the default path with status code 307:
$router->location(null, 307);
# null (script execution terminated)

// Redirect to an arbitrary URL:
$router->location('http://gajus.com');
# null (script execution terminated)
```

`location` will throw `Exception\LogicException` exception if [headers have been already sent](http://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php).

### Get path

The iverse of the `url` method is `getPath`. It is used to get the resource path of the current request URI relative to a specific route:

```php
// Taken from ./tests/RouterTest.php

$router = new \Gajus\Director\Router('https://gajus.com/foo/');

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
