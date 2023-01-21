<?php

declare(strict_types=1);

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Kernel\App\Business\Event;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Kernel\App\AppKernelInterface;

/**
 * @author Stanislau Komar <head.trackingsoft@gmail.com>
 */
interface ApplicationReadyEventInterface extends EventInterface
{
    public function kernel(): AppKernelInterface;

    /**
     * Returns application environment. APP_ENV.
     */
    public function environment(): string;

    /**
     * Returns PHP_SAPI env value by default.
     */
    public function systemEnvironment(): string;
}
