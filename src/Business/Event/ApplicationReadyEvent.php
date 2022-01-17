<?php

namespace Micro\Kernel\App\Business\Event;

use Micro\Component\EventEmitter\EventInterface;

class ApplicationReadyEvent implements EventInterface
{
    /**
     * @param $environment
     */
    public function __construct(private $environment)
    {
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
