<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Kernel\App;

use Micro\Component\DependencyInjection\ContainerInterface;
use Micro\Framework\Kernel\AppModeEnum;
use Micro\Framework\Kernel\Boot\ConfigurationProviderBootLoader;
use Micro\Framework\Kernel\Boot\DependedPluginsBootLoader;
use Micro\Framework\Kernel\Boot\DependencyProviderBootLoader;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Kernel;
use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Kernel\App\Business\KernelRunActionProcessor;
use Micro\Kernel\App\Business\KernelTerminateActionProcessor;
use Micro\Plugin\EventEmitter\EventEmitterPlugin;
use Micro\Plugin\Locator\LocatorPlugin;

class AppKernel implements AppKernelInterface
{
    private bool $isTerminated;

    private bool $isStarted;

    private readonly KernelInterface $kernel;

    /**
     * @var PluginBootLoaderInterface[]
     */
    private array $additionalBootLoaders = [];

    /**
     * @param ApplicationConfigurationInterface|array<string, string> $configuration
     * @param class-string[]                                          $plugins
     */
    public function __construct(
        private readonly ApplicationConfigurationInterface|array $configuration = [],
        private array $plugins = [],
        private readonly string $environment = AppModeEnum::DEV->value,
    ) {
        $this->kernel = $this->createKernel();
        $this->isTerminated = false;
        $this->isStarted = false;
    }

    public function container(): ContainerInterface
    {
        return $this->kernel()->container();
    }

    public function plugins(?string $interfaceInherited = null): \Traversable
    {
        return $this->kernel()->plugins($interfaceInherited);
    }

    public function run(): void
    {
        if ($this->isStarted) {
            return;
        }

        $this->kernel->run();
        $this->createInitActionProcessor()->process($this);
        $this->isStarted = true;
    }

    public function terminate(): void
    {
        if ($this->isTerminated || !$this->isStarted) {
            return;
        }

        $this->createTerminateActionProcessor()->process($this);
        $this->isTerminated = true;
    }

    public function environment(): string
    {
        return $this->environment;
    }

    public function isDevMode(): bool
    {
        return $this->kernel()->getMode()->isDev();
    }

    public function addBootLoader(PluginBootLoaderInterface $bootLoader): self
    {
        $this->additionalBootLoaders[] = $bootLoader;

        return $this;
    }

    public function loadPlugin(string $applicationPluginClass): void
    {
        $this->kernel()->loadPlugin($applicationPluginClass);
    }

    protected function createKernel(): KernelInterface
    {
        /** @var class-string[] $plugins */
        $plugins = array_unique([
            EventEmitterPlugin::class,
            LocatorPlugin::class,
            ...$this->plugins,
        ]);

        $this->plugins = [];

        return new Kernel(
            $plugins,
            $this->createBootLoaderCollection(),
            AppModeEnum::fromString($this->environment),
        );
    }

    protected function kernel(): KernelInterface
    {
        if (!$this->isStarted) {
            $trace = debug_backtrace();
            $caller = $trace[1];
            /**
             * @var class-string $cc
             *
             * @psalm-suppress PossiblyUndefinedArrayOffset
             */
            $cc = $caller['class'] ?? __CLASS__;
            $cm = $caller['function'];

            throw new \RuntimeException(\sprintf('Method %s::%s can not be called before %s::run() execution.', $cc, $cm, KernelInterface::class));
        }

        return $this->kernel;
    }

    protected function createInitActionProcessor(): KernelActionProcessorInterface
    {
        return new KernelRunActionProcessor();
    }

    protected function createTerminateActionProcessor(): KernelActionProcessorInterface
    {
        return new KernelTerminateActionProcessor();
    }

    /**
     * @return PluginBootLoaderInterface[]
     */
    protected function createBootLoaderCollection(): array
    {
        $bl = $this->additionalBootLoaders;
        $this->additionalBootLoaders = [];

        return [
            new ConfigurationProviderBootLoader($this->configuration),
            new DependencyProviderBootLoader($this->container()),
            new DependedPluginsBootLoader($this),
            ...$bl,
        ];
    }

    public function setBootLoaders(iterable $bootLoaders): KernelInterface
    {
        $this->kernel()->setBootLoaders($bootLoaders);

        return $this;
    }

    public function getMode(): AppModeEnum
    {
        return $this->kernel()->getMode();
    }
}
