<?php
class InverseURLTest extends PHPUnit_Framework_TestCase {
    public function testGetPath () {
        $locator = new \Gajus\Director\Locator('https://gajus.com/foo/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $this->assertSame('', $locator->getPath());

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/bar/';

        $this->assertSame('bar/', $locator->getPath());

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/bar/?foo[bar]=1';

        $this->assertSame('bar/', $locator->getPath());
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Route is using a different scheme.
     */
    public function testGetPathDifferentScheme () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/foo/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $locator->getPath();
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Route has a different host.
     */
    public function testGetPathDifferentHost () {
        $locator = new \Gajus\Director\Locator('https://gajus.com/foo/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.io';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $locator->getPath();
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Request URI does not extend the route.
     */
    public function testGetPathRouteURINotUnderTheRoute () {
        $locator = new \Gajus\Director\Locator('https://gajus.com/foo/bar/');

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/';

        $locator->getPath();

        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'gajus.com';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $locator->getPath();
    }
}