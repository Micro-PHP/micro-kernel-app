<?php

namespace Micro\Kernel\App\Business;

use Micro\Component\DependencyInjection\Container;
use Micro\Component\EventEmitter\ListenerProviderInterface;
use Micro\Kernel\App\Business\Processor\AppCreateEventRunSuccess;
use Micro\Kernel\App\Business\Processor\PluginEventsListenerRegistration;
use Micro\Kernel\App\Business\Processor\ProvideKernelProcessor;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;

/**
 * @deprecated
 */
class KernelRunActionProcessor extends AbstractActionProcessor
{
    /**
     * {@inheritDoc}
     */
    protected function createActionProcessorCollection(): array
    {
        return [
            $this->createProvideKernelProcessor(),
        ];
    }
    /**
     * @return KernelActionProcessorInterface
     */
    protected function createProvideKernelProcessor(): KernelActionProcessorInterface
    {
        return new ProvideKernelProcessor();
    }
}
