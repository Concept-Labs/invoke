<?php
namespace Cl\Invoke\Timeout\Pcntl;

use Cl\Generator\Generator;

final class InvokerGenerator extends Invoker
{
    /**
     * {@inheritDoc}
     */
    public static function invoke(callable|\Generator $callable, array $arguments, ?int $timeout = self::DEFAULT_TIMEOUT): \Generator
    {
        return Generator::yield(
            match (true) {
                is_callable($callable) => parent::invoke(callable: $callable, arguments: $arguments),
                default => $callable
            }
        );
    }
}