<?php

namespace Micro\Kernel\App;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Boot\ConfigurationProviderBootLoader;
use Micro\Framework\Kernel\Boot\DependencyProviderBootLoader;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Micro\Framework\Kernel\Container\Impl\ApplicationContainerFactory;
use Micro\Framework\Kernel\KernelBuilder;
use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Kernel\App\Business\KernelRunActionProcessor;
use Micro\Kernel\App\Business\KernelTerminateActionProcessor;
use Micro\Plugin\EventEmitter\EventEmitterPlugin;
use Psr\Container\ContainerInterface;

class AppKernel implements AppKernelInterface
{
    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container;

    /**
     * @var PluginBootLoaderInterface[]
     */
    private iterable $additionalBootLoaders = [];

    /**
     * @param ApplicationConfigurationInterface|array $configuration
     * @param array $plugins
     * @param string $environment
     */
    public function __construct(
        private ApplicationConfigurationInterface|array $configuration = [],
        private array $plugins = [],
        private readonly string $environment = 'dev'
    )
    {
        $this->container = $this->createApplicationContainerFactory()->create();
        $this->kernel    = $this->createKernel();
    }

    /**
     * {@inheritDoc}
     */
    public function container(): Container
    {
        return $this->kernel->container();
    }

    /**
     * {@inheritDoc}
     */
    public function plugins(string $interfaceInherited = null): iterable
    {
        return $this->kernel->plugins($interfaceInherited);
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this->kernel->run();

        $this->createInitActionProcessor()->process($this);
    }

    /**
     * {@inheritDoc}
     */
    public function terminate(): void
    {
        $this->createTerminateActionProcessor()->process($this);

        $this->kernel->terminate();
    }

    /**
     * {@inheritDoc}
     */
    public function environment(): string
    {
        return $this->environment;
    }

    /**
     * {@inheritDoc}
     */
    public function isDevMode(): bool
    {
        return $this->environment() === 'dev';
    }

    /**
     * @param PluginBootLoaderInterface $pluginBootLoader
     *
     * @return $this
     */
    public function addBootLoader(PluginBootLoaderInterface $pluginBootLoader): self
    {
        $this->additionalBootLoaders[] = $pluginBootLoader;

        return $this;
    }

    /**
     * @return KernelInterface
     */
    protected function createKernel(): KernelInterface
    {
        $kernel = $this
            ->createKernelBuilder()
            ->addBootLoaders($this->createBootLoaderCollection())
            ->setContainer($this->container)
            ->setApplicationPlugins(
                [
                    EventEmitterPlugin::class,
                    ...$this->plugins
                ]
            )
            ->build();

        $this->container = null;

        return $kernel;
    }

    /**
     * @return KernelBuilder
     */
    protected function createKernelBuilder(): KernelBuilder
    {
        return new KernelBuilder();
    }

    /**
     * @return KernelActionProcessorInterface
     */
    protected function createInitActionProcessor(): KernelActionProcessorInterface
    {
        return new KernelRunActionProcessor($this->container());
    }

    /**
     * @return KernelActionProcessorInterface
     */
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
            new DependencyProviderBootLoader($this->container),
            new ConfigurationProviderBootLoader($this->configuration),
            ...$bl
        ];
    }

    /**
     * @return ApplicationContainerFactoryInterface
     */
    protected function createApplicationContainerFactory(): ApplicationContainerFactoryInterface
    {
        return new ApplicationContainerFactory();
    }
}
