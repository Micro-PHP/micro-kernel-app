<?php

namespace Micro\Kernel\App;

use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

/**
 * Used to decorate the `micro/kernel` and simplify its creation.
 * By default, plugin configuration initialization and dependency provider initialization loaders have been added.
 *
 * **Installation**
 * ```bash
 * $ composer require micro/kernel-app
 * ```
 *
 * **Basic example**
 * ```php
 *   $applicationConfiguration = new class extends DefaultApplicationConfiguration {
 *
 *      private readonly Dotenv $dotenv;
 *
 *      public function __construct()
 *      {
 *          $basePath = dirname(__FILE__) . '/../';
 *          $_ENV['BASE_PATH'] =  $basePath;
 *          $env = getenv('APP_ENV') ?: 'dev';
 *
 *          $envFileCompiled = $basePath . '/' .  '.env.' .$env . '.php';
 *          if(file_exists($envFileCompiled)) {
 *              $content = include $envFileCompiled;
 *              parent::__construct($content);
 *
 *              return;
 *          }
 *
 *          $names[] = '.env';
 *          $names[] = '.env.' . $env;
 *          // Dotenv library is not included by default. Used for example.
 *          $this->dotenv = Dotenv::createMutable($basePath, $names, false);
 *          $this->dotenv->load();
 *
 *          parent::__construct($_ENV);
 *      }
 *   };
 *
 *   $kernel =  new AppKernel(
 *      $applicationConfiguration,
 *      [
 *          SomePlugin::class,
 *          AnotherPlugin::class,
 *      ],
 *      $applicationConfiguration->get('APP_ENV', 'dev')
 *   );
 *
 *   $kernel->run();
 * ```
 *
 * @api
 */
interface AppKernelInterface extends KernelInterface
{
    /**
     * @return string
     */
    public function environment(): string;

    /**
     * @return bool
     */
    public function isDevMode(): bool;

    /**
     * @param PluginBootLoaderInterface $pluginBootLoader
     *
     * @return $this
     */
    public function addBootLoader(PluginBootLoaderInterface $pluginBootLoader): self;
}
