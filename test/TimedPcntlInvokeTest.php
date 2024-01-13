<?php

namespace Cl\Invoke\Test;

use PHPUnit\Framework\TestCase;

use Cl\Invoke\Timeout\Pcntl\Invoker;
use Cl\Invoke\Exception\TimeoutException;

/**
 * @covers Cl\Invoke\Timeout\Pcntl\Invoker
 */
class TimedPcntlInvokeTest extends TestCase
{
    public function testInvokeCallableSuccessfully()
    {
        $callable = function ($a, $b) {
            return $a + $b;
        };

        $arguments = [3, 4];
        $timeout = 1;

        $result = Invoker::invoke($callable, $arguments, $timeout);

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

        Invoker::invoke($callable, $arguments, $timeout);
    }

    public function testInvokeCallableWithDefaultTimeout()
    {
        $callable = function ($a, $b) {
            return $a + $b;
        };

        $arguments = [3, 4];

        $result = Invoker::invoke($callable, $arguments);

        $this->assertEquals(7, $result);
    }

    public function testInvokeCallableWithDefaultTimeoutException()
    {
        $this->expectException(TimeoutException::class);

        $callable = function () {
            sleep(2);
        };

        $arguments = [];

        Invoker::invoke($callable, $arguments);
    }


    public function testInvokeMagicMethodSuccessfully()
    {
        $callable = function ($a, $b) {
            return $a * $b;
        };

        $arguments = [3, 4];
        $timeout = 1;

        $timedInvoke = new Invoker();
        $result = $timedInvoke($callable, $arguments, $timeout);

        $this->assertEquals(12, $result);
    }
}