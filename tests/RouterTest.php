<?php
class RouterTest extends PHPUnit_Framework_TestCase {
    public function testSetDefaultRoute () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $this->assertSame('http://gajus.com/', $router->getRoute('default'));
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid URL.
     */
    public function testSetRouteInvalidURL () {
        $router = new \Gajus\Director\Router('foo');
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage URL does not refer to a directory.
     */
    public function testSetRouteURLNotBase () {
        $router = new \Gajus\Director\Router('http://gajus.com');
    }

    public function testSetCustomRoute () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $router->setRoute('foo', 'http://gajus.com/foo/');

        $this->assertSame('http://gajus.com/foo/', $router->getRoute('foo'));
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Cannot overwrite existing route.
     */
    public function testOverwriteExistingRoute () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $router->setRoute('foo', 'http://gajus.com/foo/');
        $router->setRoute('foo', 'http://gajus.com/foo/');
    }

    public function testGetURLDefaultRoute () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $this->assertSame('http://gajus.com/', $router->url());
    }

    public function testGetURLUsingCustomPath () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $this->assertSame('http://gajus.com/foo', $router->url('foo'));
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Route does not exist.
     */
    public function testGetURLNotExistingRoute () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $router->url('foo', 'foobar');
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Path is not relative to the route.
     */
    public function testGetURLUsingAbsoluteCustomPath () {
        $router = new \Gajus\Director\Router('http://gajus.com/');

        $router->url('/foo');
    }

    public function testGetPath () {
        $router = new \Gajus\Director\Router('https://gajus.com/foo/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $this->assertSame('', $router->getPath());

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/bar/';

        $this->assertSame('bar/', $router->getPath());

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/bar/?foo[bar]=1';

        $this->assertSame('bar/', $router->getPath());
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Route is using a different scheme.
     */
    public function testGetPathDifferentScheme () {
        $router = new \Gajus\Director\Router('http://gajus.com/foo/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $router->getPath();
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Route has a different host.
     */
    public function testGetPathDifferentHost () {
        $router = new \Gajus\Director\Router('https://gajus.com/foo/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.io';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $router->getPath();
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Request URI does not extend the route.
     */
    public function testGetPathRouteURINotUnderTheRoute () {
        $router = new \Gajus\Director\Router('https://gajus.com/foo/bar/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/';

        $router->getPath();

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $router->getPath();
    }
}