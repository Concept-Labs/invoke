<?php
namespace Cl\Invoke;

use Cl\Invoke\Exception\TimeoutException;

final class TimedInvoke
{
    const DEFAULT_TIMEOUT = 2; // seconds

    /**
     * @see static::invoke()
     */
    public function __invoke(callable $callable, array $arguments, ?int $timeout = null): mixed
    {
        return self::invoke($callable, $arguments, $timeout);
    }

    /**
     * Invoke a callable with arguments and a timeout limit.
     *
     * @param callable $callable  The callable to invoke.
     * @param array    $arguments The arguments to pass to the callable.
     * @param int|null $timeout   The timeout limit in seconds.
     *
     * @return mixed The result of the callable.
     * @throws TimeoutException If the callable execution exceeds the timeout limit.
     * @throws CallException    If an error occurs during callable execution.
     */
    public static function invoke(callable $callable, array $arguments, ?int $timeout = null): mixed
    {
        $timeout ??= self::DEFAULT_TIMEOUT;
        $result = null;

        // Define a signal handler function for the alarm signal.
        $signalHandler = function () use (&$error) {
            throw new TimeoutException('TimedInvoke: Callable execution timed out.');
        };

        // Set the signal handler for the alarm signal.
        pcntl_signal(SIGALRM, $signalHandler);

        pcntl_async_signals(true);

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