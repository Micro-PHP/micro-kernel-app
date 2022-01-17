<?php

namespace Micro\Kernel\App\Business;

use Micro\Component\DependencyInjection\Container;

interface ApplicationListenerProviderPluginInterface
{
    /**
     * @return array
     */
    public function provideEventListeners(): array;
}
