<?php
namespace Cl\Invoke;

use Cl\Generator\Generator;

final class InvokerGenerator extends Invoker
{
    /**
     * {@inheritDoc}
     */
    public static function invoke(callable|\Generator $callable, array $arguments): \Generator
    {
        return Generator::yield(
            match (true) {
                is_callable($callable) => parent::invoke(callable: $callable, arguments: $arguments),
                default => $callable
            }
        );
    }

}