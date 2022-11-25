<?php

namespace Micro\Kernel\App;

use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

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

    /**
     * @param PluginBootLoaderInterface $pluginBootLoader
     *
     * @return $this
     */
    public function addBootLoader(PluginBootLoaderInterface $pluginBootLoader): self;
}
