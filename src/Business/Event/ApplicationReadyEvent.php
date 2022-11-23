<?php

namespace Micro\Kernel\App\Business\Event;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;

class ApplicationReadyEvent implements EventInterface
{
    /**
     * @param AppKernelInterface $appKernel
     * @param string $environment
     */
    public function __construct(private AppKernelInterface $appKernel, private string $environment)
    {
    }

    /**
     * @return AppKernelInterface
     */
    public function kernel(): AppKernelInterface
    {
        return $this->appKernel;
    }

    /**
     * @return string
     */
    public function environment(): string
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function systemEnvironment(): string
    {
        return PHP_SAPI;
    }
}
