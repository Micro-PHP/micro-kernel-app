<?php

namespace Micro\Kernel\App\Business;

use Micro\Kernel\App\AppKernelInterface;
use Micro\Kernel\App\Business\Processor\AppCreateEventTerminate;

/**
 * @deprecated
 */
class KernelTerminateActionProcessor extends AbstractActionProcessor
{
    /**
     * {@inheritDoc}
     */
    protected function createActionProcessorCollection(): array
    {
        return [
            new AppCreateEventTerminate(),
        ];
    }
}
