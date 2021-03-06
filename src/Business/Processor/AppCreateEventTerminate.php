<?php

namespace Micro\Kernel\App\Business\Processor;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\Event\ApplicationTerminatedEvent;

class AppCreateEventTerminate extends AbstractEmitEventProcessor
{
    /**
     * {@inheritDoc}
     */
    protected function createEvent(AppKernelInterface $appKernel): EventInterface
    {
        return new ApplicationTerminatedEvent();
    }
}
