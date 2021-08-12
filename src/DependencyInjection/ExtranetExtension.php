<?php

namespace AcMarche\Extranet\DependencyInjection;

use Symfony\Component\Config\Builder\ConfigBuilderGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see https://symfony.com/doc/bundles/prepend_extension.html
 */
class ExtranetExtension extends Extension implements PrependExtensionInterface
{
    private YamlFileLoader $loader;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->loader->load('services.yaml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->loader = $this->initPhpFilerLoader($container);

        foreach (array_keys($container->getExtensions()) as $name) {
            if ($name === 'twig') {
                $this->loadConfig('twig');
            }
        }
    }

    protected function loadConfig(string $name): void
    {
        $this->loader->load('packages/'.$name.'.yaml');
    }

    protected function initPhpFilerLoader(ContainerBuilder $containerBuilder): YamlFileLoader
    {
        return new YamlFileLoader(
            $containerBuilder,
            new FileLocator(__DIR__.'/../../config/'),
            null,
            class_exists(ConfigBuilderGenerator::class) ? new ConfigBuilderGenerator(
                $containerBuilder->getParameter('kernel.cache_dir')
            ) : null
        );
    }
}
