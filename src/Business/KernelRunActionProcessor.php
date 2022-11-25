<?php

namespace Micro\Kernel\App\Business;

use Micro\Kernel\App\Business\Processor\AppCreateEventRunSuccess;
use Micro\Kernel\App\Business\Processor\ProvideKernelProcessor;

class KernelRunActionProcessor extends AbstractActionProcessor
{
    /**
     * {@inheritDoc}
     */
    protected function createActionProcessorCollection(): array
    {
        return [
            $this->createProvideKernelProcessor(),
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
    protected function createProvideKernelProcessor(): KernelActionProcessorInterface
    {
        return new ProvideKernelProcessor();
    }
}
