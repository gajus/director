<?php
class InverseURLTest extends PHPUnit_Framework_TestCase {
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