<?php

namespace Micro\Kernel\App\Business;

use Micro\Kernel\App\AppKernelInterface;

abstract class AbstractActionProcessor implements KernelActionProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(AppKernelInterface $appKernel): void
    {
        foreach ($this->createActionProcessorCollection() as $actionProcessor) {
            $actionProcessor->process($appKernel);
        }
    }

    /**
     * @return KernelActionProcessorInterface[]
     */
    abstract protected function createActionProcessorCollection(): array;
}
