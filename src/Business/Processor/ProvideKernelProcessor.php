<?php

namespace Micro\Kernel\App\Business\Processor;

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
        $appKernel->container()->register(AppKernelInterface::class, function () use ($appKernel) {
            return $appKernel;
        });
    }
}
