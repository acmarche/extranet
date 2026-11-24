<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'entity_managers' => [
                'default' => [
                    'mappings' => [
                        'AcMarcheTaxe' => [
                            'is_bundle' => false,
                            'type' => 'attribute',
                            'dir' => '%kernel.project_dir%/src/AcMarche/Taxe/src/Entity',
                            'prefix' => 'AcMarche\\Taxe',
                        ],
                    ],
                ],
            ],
        ],
    ]);
};
