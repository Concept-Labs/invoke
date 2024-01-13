<?php
declare(strict_types=1);
namespace Cl\Invoke;

interface InvokerStaticInterface
{
    /**
     * Invoke a callable with arguments and a timeout limit.
     *
     * @param callable $callable  The callable to invoke.
     * @param array    $arguments The arguments to pass to the callable.
     *
     * @return mixed                              
     *  The result of the callable.
     * @throws TimeoutException                   
     *  If the callable execution exceeds the timeout limit.
     * @throws TimedInvokePcntlNotLoadedException 
     *  If an error occurs during callable execution.
     */
    static function invoke(callable $callable, array $arguments): mixed;
}