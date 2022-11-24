<?php

namespace Micro\Kernel\App\Listener;

use Micro\Component\EventEmitter\ListenerProviderInterface;

/**
 * @deprecated
 */
interface ApplicationListenerProviderPluginInterface
{
    /**
     * @return ListenerProviderInterface
     */
    public function getEventListenerProvider(): ListenerProviderInterface;
}
