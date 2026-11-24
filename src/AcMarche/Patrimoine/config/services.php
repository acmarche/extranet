<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->load('AcMarche\\Patrimoine\\', __DIR__ . '/../src/*')
        ->exclude([__DIR__ . '/../src/{Entity,Tests}']);
};
/**
 * kernel.listener.prefix:
    class: AcMarche\Patrimoine\Doctrine\TablePrefix
    arguments:
      $prefix: '%table_prefix%'
      $namespace: 'AcMarche\\Patrimoine'#double \\
    tags:
      - { name: doctrine.event_listener, event: loadClassMetadata, method: loadClassMetadata }
 */