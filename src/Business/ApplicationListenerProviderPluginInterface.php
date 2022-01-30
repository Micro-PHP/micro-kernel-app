<?php

namespace Micro\Kernel\App\Business;

use Micro\Component\EventEmitter\ListenerProviderInterface;

interface ApplicationListenerProviderPluginInterface
{
    /**
     * @return ListenerProviderInterface
     */
    public function getEventListenerProvider(): ListenerProviderInterface;
}
