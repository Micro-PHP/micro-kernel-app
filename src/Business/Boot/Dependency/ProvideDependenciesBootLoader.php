<?php

namespace Micro\Kernel\App\Business\Boot\Dependency;

use Micro\Component\DependencyInjection\Autowire\ContainerAutowire;
use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

class ProvideDependenciesBootLoader implements PluginBootLoaderInterface
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = new ContainerAutowire($container);
    }

    /**
     * {@inheritDoc}
     */
    public function boot(ApplicationPluginInterface $applicationPlugin): void
    {
        $applicationPlugin->provideDependencies($this->container);
    }
}
