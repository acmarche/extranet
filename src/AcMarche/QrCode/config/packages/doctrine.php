<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'connections' => [
                'qrcode' => [
                    'url' => '%env(resolve:DATABASE_QRCODE_URL)%',
                    'charset' => 'utf8mb4_unicode_ci',
                ],
            ],
        ],
        'orm' => [
            'entity_managers' => [
                'qrcode' => [
                    'connection' => 'qrcode',
                    'mappings' => [
                        'AcMarcheQrCode' => [
                            'is_bundle' => false,
                            'type' => 'attribute',
                            'dir' => '%kernel.project_dir%/src/AcMarche/QrCode/src/Entity',
                            'prefix' => 'AcMarche\\QrCode',
                        ],
                    ],
                ],
            ],
        ],
    ]);
};
