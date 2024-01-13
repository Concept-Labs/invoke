<?php
namespace Cl\Invoke\Timeout\Pcntl;

use Cl\Able\Assertable\AssertableStaticInterface;
use Cl\Able\Invokeable\InvokeableInterface;
use Cl\Invoke\Exception\TimedInvokePcntlNotLoadedException;
use Cl\Invoke\Exception\TimeoutException;
use Cl\Invoke\InvokerStaticInterface;
use Cl\Invoke\Timeout\InvokerTimeoutInterface;

class Invoker implements  InvokerStaticInterface,
                                InvokeableInterface,
                                AssertableStaticInterface,
                                InvokerTimeoutInterface
{
    use AssertTrait;
    use InvokeableTrait;

    /**
     * Invoke a callable with arguments and a timeout limit.
     *
     * @param callable $callable  The callable to invoke.
     * @param array    $arguments The arguments to pass to the callable.
     * @param int|null $timeout   The timeout limit in seconds.
     *
     * @return mixed                              
     *  The result of the callable.
     * @throws TimeoutException                   
     *  If the callable execution exceeds the timeout limit.
     * @throws TimedInvokePcntlNotLoadedException 
     *  If an error occurs during callable execution.
     */
    public static function invoke(callable $callable, array $arguments, ?int $timeout = self::DEFAULT_TIMEOUT): mixed
    {
        //assert class
        static::assert();

        // Define a signal handler function for the alarm signal.
        $signalHandler = function () {
            throw new TimeoutException(sprintf('%s: Callable execution timed out.', static::class));
        };

        // Set the signal handler for the alarm signal.
        pcntl_signal(SIGALRM, $signalHandler);

        if (function_exists('pcntl_async_signals')) {
            pcntl_async_signals(true);
        }

        // Schedule the alarm signal after the specified timeout.
        pcntl_alarm($timeout);

        try {
            // Execute the callable.
            $result = call_user_func_array($callable, $arguments);
        } finally {

            // Disable the alarm signal.
            pcntl_alarm(0);

            // Reset the signal handler.
            pcntl_signal(SIGALRM, SIG_DFL);
        }

        // Return the result of the callable.
        return $result;
    }
}