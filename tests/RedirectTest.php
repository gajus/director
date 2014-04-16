<?php
class RedirectTest extends PHPUnit_Framework_TestCase {
    /**
     * @expectedException Gajus\Director\Exception\LogicException
     * @expectedExceptionMessage Redirect cannot be performed in the CLI.
     */
    public function testCannotRedirectInCLI () {
        $router = new \Gajus\Director\Router('http://gajus.com/');
        $router->location();
    }
}