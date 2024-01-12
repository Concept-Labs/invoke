<?php

use PHPUnit\Framework\TestCase;

use Cl\Invoke\TimedInvoke;
use Cl\Invoke\Exception\CallException;
use Cl\Invoke\Exception\TimeoutException;

/**
 * @covers Cl\Invoke\TimedInvoke
 */
class TimedInvokeTest extends TestCase
{
    public function testInvokeCallableSuccessfully()
    {
        $callable = function ($a, $b) {
            return $a + $b;
        };

        $arguments = [3, 4];
        $timeout = 1;

        $result = TimedInvoke::invoke($callable, $arguments, $timeout);

        $this->assertEquals(7, $result);
    }

    public function testInvokeCallableWithTimeoutException()
    {
        $this->expectException(TimeoutException::class);

        $callable = function () {
            sleep(2);
        };

        $arguments = [];
        $timeout = 1;

        TimedInvoke::invoke($callable, $arguments, $timeout);
    }

    public function testInvokeCallableWithDefaultTimeout()
    {
        $callable = function ($a, $b) {
            return $a + $b;
        };

        $arguments = [3, 4];

        $result = TimedInvoke::invoke($callable, $arguments);

        $this->assertEquals(7, $result);
    }

    public function testInvokeCallableWithDefaultTimeoutException()
    {
        $this->expectException(TimeoutException::class);

        $callable = function () {
            sleep(2);
        };

        $arguments = [];

        TimedInvoke::invoke($callable, $arguments);
    }

    public function testInvokeMagicMethodSuccessfully()
    {
        $callable = function ($a, $b) {
            return $a * $b;
        };

        $arguments = [3, 4];
        $timeout = 1;

        $timedInvoke = new TimedInvoke();
        $result = $timedInvoke($callable, $arguments, $timeout);

        $this->assertEquals(12, $result);
    }
}