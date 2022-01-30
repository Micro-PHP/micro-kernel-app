<?php

namespace Micro\Kernel\App\Business\Processor;

use Micro\Component\DependencyInjection\Container;
use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;

abstract class AbstractEmitEventProcessor implements KernelActionProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(AppKernelInterface $appKernel): void
    {
        $event = $this->createEvent($appKernel);

        $this->lookupEventEmitter($appKernel->container())->emit($event);
    }

    /**
     * @param  AppKernelInterface $appKernel
     * @return EventInterface
     */
    abstract protected function createEvent(AppKernelInterface $appKernel): EventInterface;

    /**
     * @param Container $container
     * @return EventsFacadeInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function lookupEventEmitter(Container $container): EventsFacadeInterface
    {
        return $container->get(EventsFacadeInterface::class);
    }
}
