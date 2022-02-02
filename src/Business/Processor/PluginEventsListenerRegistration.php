<?php

namespace Micro\Kernel\App\Business\Processor;

use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Component\EventEmitter\ListenerProviderInterface;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\ApplicationListenerProviderPluginInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;

class PluginEventsListenerRegistration implements KernelActionProcessorInterface
{
    /**
     * @param EventsFacadeInterface $eventsFacade
     */
    public function __construct(private EventsFacadeInterface $eventsFacade)
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
            /** @var ApplicationListenerProviderPluginInterface $plugin */
            $this->registerPluginListenerProvider($plugin);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param ApplicationListenerProviderPluginInterface $applicationPlugin
     */
    public function registerPluginListenerProvider(ApplicationListenerProviderPluginInterface $applicationPlugin): void
    {
        $this->eventsFacade->addProvider($applicationPlugin->getEventListenerProvider());
    }

    /**
     * @param  ApplicationPluginInterface $applicationPlugin
     * @return bool
     */
    protected function supports(ApplicationPluginInterface $applicationPlugin): bool
    {
        return $applicationPlugin instanceof ApplicationListenerProviderPluginInterface;
    }
}
