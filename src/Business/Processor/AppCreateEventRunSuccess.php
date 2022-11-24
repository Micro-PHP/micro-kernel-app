<?php

namespace Micro\Kernel\App\Business\Processor;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\Event\ApplicationReadyEvent;

/**
 * @deprecated
 */
class AppCreateEventRunSuccess extends AbstractEmitEventProcessor
{
    /**
     * {@inheritDoc}
     */
    protected function createEvent(AppKernelInterface $appKernel): EventInterface
    {
        return new ApplicationReadyEvent(
            $appKernel,
            $appKernel->environment()
        );
    }
}
