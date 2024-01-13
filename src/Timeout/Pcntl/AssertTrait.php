<?php
declare(strict_types=1);
namespace Cl\Invoke\Timeout\Pcntl;

use Cl\Invoke\Exception\TimedInvokePcntlNotLoadedException;

trait AssertTrait
{
    /**
     * {@inheritDoc}
     *
     * @throws TimedInvokePcntlNotLoadedException
     */
    public static function assert(): void
    {
        static::assertPcntl();
    }

    /**
     * Assert pcntl upport
     *
     * @return void
     * @throws TimedInvokePcntlNotLoadedException
     */
    public static function assertPcntl()
    {
        if (!static::canInvoke()) {
            throw new TimedInvokePcntlNotLoadedException(
                sprintf('%s: pcntl support is not enabled', static::class)
            );
        }
    }

    public static function canInvoke(): bool
    {
        foreach (['pcntl_signal', 'pcntl_alarm'] as $fn) {
            if (!function_exists($fn)) {
                return false;
            }
        }
        return true;
    }
}