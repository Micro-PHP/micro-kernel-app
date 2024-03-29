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

use Micro\Framework\Kernel\KernelInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;

class ProvideKernelProcessor implements KernelActionProcessorInterface
{
    public function process(AppKernelInterface $appKernel): void
    {
        $callback = fn (): KernelInterface => $appKernel;

        $appKernel->container()->register(AppKernelInterface::class, $callback);
        $appKernel->container()->register(KernelInterface::class, $callback);
    }
}
