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

use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\Event\ApplicationReadyEvent;

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
