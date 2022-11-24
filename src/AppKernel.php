<?php

namespace Micro\Kernel\App;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Boot\ConfigurationProviderBootLoader;
use Micro\Framework\Kernel\Boot\DependencyProviderBootLoader;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationFactoryInterface;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Micro\Framework\Kernel\Container\Impl\ApplicationContainerFactory;
use Micro\Framework\Kernel\KernelBuilder;
use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Kernel\App\Business\KernelRunActionProcessor;
use Micro\Kernel\App\Business\KernelTerminateActionProcessor;

class AppKernel implements AppKernelInterface
{
    /**
     * @var KernelInterface|null
     */
    private KernelInterface|null $kernel = null;

    /**
     * @var Container|null
     */
    private Container|null $container;

    /**
     * @var iterable<PluginBootLoaderInterface>
     */
    private iterable $bootLoadersCustom = [];

    /**
     * @param iterable $plugins
     * @param array|ApplicationConfigurationInterface|ApplicationConfigurationFactoryInterface $configuration
     * @param string $environment
     */
    public function __construct(
        private readonly iterable                                                                         $plugins,
        private readonly array|ApplicationConfigurationInterface|ApplicationConfigurationFactoryInterface $configuration,
        private readonly string                                                                           $environment = 'dev'
    )
    {
        $container = $this
            ->createApplicationContainerFactory()
            ->create();

        if(!($container instanceof Container)) {
            throw new \RuntimeException(sprintf('Temporary exception. Container should be %s instance object', Container::class));
        }

        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function container(): Container
    {
        return $this->createKernel()->container();
    }

    /**
     * {@inheritDoc}
     */
    public function plugins(string $interfaceInherited = null): iterable
    {
        return $this
            ->createKernel()
            ->plugins($interfaceInherited);
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this
            ->createKernel()
            ->run();

        $this
            ->createInitActionProcessor()
            ->process($this);
    }

    /**
     * {@inheritDoc}
     */
    public function terminate(): void
    {
        $this
            ->createTerminateActionProcessor()
            ->process($this);

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
     * @return KernelInterface
     */
    protected function createKernel(): KernelInterface
    {
        if($this->kernel !== null) {
            return $this->kernel;
        }

        $kernel = $this
            ->createKernelBuilder()
            ->addBootLoaders($this->createBootLoaderCollection())
            ->setContainer($this->container)
            ->setApplicationPlugins($this->plugins)
            ->build();

        $this->kernel = $kernel;

        return $this->kernel;
    }

    /**
     * @return KernelBuilder
     */
    protected function createKernelBuilder(): KernelBuilder
    {
        return new KernelBuilder();
    }

    /**
     * @deprecated
     *
     * @return KernelActionProcessorInterface
     */
    protected function createInitActionProcessor(): KernelActionProcessorInterface
    {
        return new KernelRunActionProcessor();
    }

    /**
     * @deprecated
     *
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
        return [
            new DependencyProviderBootLoader($this->container),
            new ConfigurationProviderBootLoader($this->configuration),
            ...$this->bootLoadersCustom
        ];
    }

    /**
     * @param PluginBootLoaderInterface $pluginBootLoader
     *
     * @return $this
     */
    public function addBootLoader(PluginBootLoaderInterface $pluginBootLoader): self
    {
        $this->bootLoadersCustom[] = $pluginBootLoader;

        return $this;
    }

    /**
     * @return ApplicationContainerFactoryInterface
     */
    protected function createApplicationContainerFactory(): ApplicationContainerFactoryInterface
    {
        return new ApplicationContainerFactory();
    }
}
