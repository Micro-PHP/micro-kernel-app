<?php

namespace Micro\Kernel\App\Business\Processor;

use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Component\EventEmitter\ListenerProviderInterface;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\ApplicationListenerProviderPluginInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;


class PluginEventsListenerRegistration implements KernelActionProcessorInterface
{
    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(private ListenerProviderInterface $listenerProvider)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function process(AppKernelInterface $appKernel): void
    {
        foreach ($appKernel->plugins() as $plugin) {
            if($this->supports($plugin) === false) {
                continue;
            }

            $this->registerPluginListeners($plugin);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param ApplicationListenerProviderPluginInterface $applicationPlugin
     */
    public function registerPluginListeners(ApplicationPluginInterface $applicationPlugin): void
    {
        foreach ($applicationPlugin->provideEventListeners() as $listener) {
            $this->registerListener($listener);
        }
    }

    /**
     * @param EventListenerInterface $listener
     * @return void
     */
    protected function registerListener(EventListenerInterface $listener): void
    {
        $this->listenerProvider->registerListener($listener);
    }

    /**
     * @param ApplicationPluginInterface $applicationPlugin
     * @return bool
     */
    protected function supports(ApplicationPluginInterface $applicationPlugin): bool
    {
        return $applicationPlugin instanceof ApplicationListenerProviderPluginInterface;
    }
}
