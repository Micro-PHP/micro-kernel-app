<?php

namespace Micro\Kernel\App\Business;

use Micro\Framework\Kernel\KernelInterface;
use Micro\Kernel\App\AppKernelInterface;

/**
 * @deprecated
 */
interface KernelActionProcessorInterface
{
    /**
     * @param  AppKernelInterface $appKernel
     * @return void
     */
    public function process(AppKernelInterface $appKernel): void;
}
