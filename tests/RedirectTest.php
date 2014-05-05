<?php
class RedirectTest extends PHPUnit_Framework_TestCase {
    /**
     * @expectedException Gajus\Director\Exception\LogicException
     * @expectedExceptionMessage Redirect cannot be performed in the CLI.
     */
    public function testCannotRedirectInCLI () {
        $locator = new \Gajus\Director\Locator('http://gajus.com/');
        $locator->location();
    }
}