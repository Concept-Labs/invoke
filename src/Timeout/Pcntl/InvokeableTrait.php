<?php
declare(strict_types=1);
namespace Cl\Invoke\Timeout\Pcntl;

trait InvokeableTrait
{
    /**
     * @see static::invoke()
     */
    public function __invoke(...$args): mixed
    {
        return static::invoke(...$args);
    }

}