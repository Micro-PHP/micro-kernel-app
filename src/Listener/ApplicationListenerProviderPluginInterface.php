<?php

namespace Micro\Kernel\App\Listener;

use Micro\Component\EventEmitter\ListenerProviderInterface;

interface ApplicationListenerProviderPluginInterface
{
    /**
     * @return ListenerProviderInterface
     */
    public function getEventListenerProvider(): ListenerProviderInterface;
}
