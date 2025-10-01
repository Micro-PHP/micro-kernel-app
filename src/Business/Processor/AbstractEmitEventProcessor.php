<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Kernel\App\Business\Processor;

use Micro\Component\DependencyInjection\ContainerInterface;
use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;

abstract class AbstractEmitEventProcessor implements KernelActionProcessorInterface
{
    public function process(AppKernelInterface $appKernel): void
    {
        $event = $this->createEvent($appKernel);

        $this->lookupEventEmitter($appKernel->container())->emit($event);
    }

    abstract protected function createEvent(AppKernelInterface $appKernel): EventInterface;

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    protected function lookupEventEmitter(ContainerInterface $container): EventsFacadeInterface
    {
        /**
         * @psalm-suppress LessSpecificReturnStatement
         */
        return $container->get(EventsFacadeInterface::class);
    }
}
