<?php

namespace Micro\Kernel\App;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Micro\Framework\Kernel\Container\Impl\ApplicationContainerFactory;
use Micro\Framework\Kernel\KernelBuilder;
use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\BootLoader\ProvideDependenciesBootLoader;
use Micro\Kernel\App\Business\KernelActionProcessorInterface;
use Micro\Kernel\App\Business\KernelRunActionProcessor;
use Micro\Kernel\App\Business\KernelTerminateActionProcessor;

class AppKernel implements AppKernelInterface
{
    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var Container|null
     */
    private ?Container $container;

    /**
     * @param ApplicationConfigurationInterface $configuration
     * @param array                             $plugins
     * @param string                            $environment
     */
    public function __construct(
    private ApplicationConfigurationInterface $configuration,
    private array $plugins,
    private string $environment = 'dev'
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
    public function plugins(): array
    {
        return $this->kernel->plugins();
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
     * @return KernelInterface
     */
    protected function createKernel(): KernelInterface
    {
        $kernel = $this
            ->createKernelBuilder()
            ->setApplicationConfiguration($this->configuration)
            ->setBootLoaders($this->createBootLoaderCollection())
            ->setContainer($this->container)
            ->setApplicationPlugins($this->plugins)
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
     * @return ProvideDependenciesBootLoader[]
     */
    protected function createBootLoaderCollection(): array
    {
        return [
            new ProvideDependenciesBootLoader($this->container),
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
