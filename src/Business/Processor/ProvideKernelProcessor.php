<?php

namespace Micro\Kernel\App\Business\Processor;

use Micro\Framework\Kernel\KernelInterface;
use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;

class ProvideKernelProcessor implements KernelActionProcessorInterface
{
    /**
     * @param AppKernelInterface $appKernel
     * @return void
     */
    public function process(AppKernelInterface $appKernel): void
    {
        $callback = fn() => $appKernel;

        $appKernel->container()->register(AppKernelInterface::class, $callback);
        $appKernel->container()->register(KernelInterface::class, $callback);
    }
}
