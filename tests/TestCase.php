<?php
/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 20/04/2016
 * Time: 2:40 PM
 */

namespace Freshdesk\tests;

use Freshdesk\Api;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    abstract function methodsThatShouldExist();

    /**
     * @var Api
     */
    protected $api;

    /**
     * The specific class being tested
     * @var
     */
    protected $class;

    public function setUp(): void
    {
        $this->api = new Api("foo", "bar");
    }

    /**
     * @dataProvider methodsThatShouldExist
     */
    public function testMethodsExist($method): void
    {
        $this->assertMethodExists($method);
    }

    //Custom Assertions

    protected function assertMethodExists($method)
    {
        $this->assertTrue(
            method_exists($this->class, $method)
        );
    }

    protected function assertEndpoint($expected, $id = null)
    {
        $actual = $this->invokeMethod('endpoint', [$id]);

        $this->assertEquals($expected, $actual);
    }

    //Helpers

    protected function invokeMethod($method, array $params)
    {
        $reflection = new \ReflectionClass($this->class::class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($this->class, $params);
    }
}
