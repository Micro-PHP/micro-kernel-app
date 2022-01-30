<?php

namespace Micro\Kernel\App;

use Micro\Framework\Kernel\KernelInterface;

interface AppKernelInterface extends KernelInterface
{
    /**
     * @return string
     */
    public function environment(): string;

    /**
     * @return bool
     */
    public function isDevMode(): bool;
}
