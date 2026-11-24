<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(
        'vich_uploader',
        [
            'mappings' => [
                'avis_file' => [
                    'uri_prefix' => '/files/enquete_publiques/avis',
                    'upload_destination' => '%kernel.project_dir%/public/files/enquete_publiques/avis',
                    'namer' => 'vich_uploader.namer_uniqid',
                    'inject_on_load' => false,
                ],
                'enquete_document' => [
                    'uri_prefix' => '/files/enquete_publiques/documents',
                    'upload_destination' => '%kernel.project_dir%/public/files/enquete_publiques/documents',
                    'namer' => 'vich_uploader.namer_uniqid',
                    'inject_on_load' => false,
                ],
            ],
        ]
    );
};