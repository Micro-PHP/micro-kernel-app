<?php

namespace Micro\Kernel\App\Business;

use Micro\Kernel\App\Business\Processor\AppCreateEventTerminate;

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
