<?php

namespace Micro\Kernel\App\Business;


use Micro\Component\DependencyInjection\Container;
use Micro\Component\EventEmitter\ListenerProviderInterface;
use Micro\Kernel\App\Business\Processor\AppCreateEventRunSuccess;
use Micro\Kernel\App\Business\Processor\PluginEventsListenerRegistration;

class KernelRunActionProcessor extends AbstractActionProcessor
{
    /**
     * @param Container $container
     */
    public function __construct(private Container $container)
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function createActionProcessorCollection(): array
    {
        return [
            $this->createPluginEventsListenerRegistration(),
            $this->createAppCreateEventRunSuccess(),
        ];
    }

    /**
     * @return KernelActionProcessorInterface
     */
    protected function createAppCreateEventRunSuccess(): KernelActionProcessorInterface
    {
        return new AppCreateEventRunSuccess();
    }

    /**
     * @return KernelActionProcessorInterface
     */
    protected function createPluginEventsListenerRegistration(): KernelActionProcessorInterface
    {
        $listenerProvider = $this->container->get(ListenerProviderInterface::class);

        return new PluginEventsListenerRegistration($listenerProvider);
    }
}
