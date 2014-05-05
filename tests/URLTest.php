<?php
class URLTest extends PHPUnit_Framework_TestCase {
    public function testSetDefaultRoute () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $this->assertSame('http://gajus.com/', $locator->getRoute('default'));
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid URL.
     */
    public function testSetRouteInvalidURL () {
        $locator = new \Gajus\Director\Locator('foo');
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage URL does not refer to a directory.
     */
    public function testSetRouteURLNotBase () {
        $locator = new \Gajus\Director\Locator('http://gajus.com');
    }

    public function testSetCustomRoute () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $locator->setRoute('foo', 'http://gajus.com/foo/');

        $this->assertSame('http://gajus.com/foo/', $locator->getRoute('foo'));
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Cannot overwrite existing route.
     */
    public function testOverwriteExistingRoute () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $locator->setRoute('foo', 'http://gajus.com/foo/');
        $locator->setRoute('foo', 'http://gajus.com/foo/');
    }

    public function testGetURLDefaultRoute () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $this->assertSame('http://gajus.com/', $locator->url());
    }

    public function testGetURLUsingCustomPath () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $this->assertSame('http://gajus.com/foo', $locator->url('foo'));
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Route does not exist.
     */
    public function testGetURLNotExistingRoute () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $locator->url('foo', 'foobar');
    }

    /**
     * @expectedException Gajus\Director\Exception\InvalidArgumentException
     * @expectedExceptionMessage Path is not relative to the route.
     */
    public function testGetURLUsingAbsoluteCustomPath () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');

        $locator->url('/foo');
    }
}