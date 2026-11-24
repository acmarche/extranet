<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'paths' => ['%kernel.project_dir%/src/AcMarche/Patrimoine/templates' => 'AcMarchePatrimoine'],
        'form_themes' => ['bootstrap_5_layout.html.twig'],
        'globals' => [
            'g_latitude' => '%env(PATRIMOINE_LATITUDE)%',
            'g_longitude' => '%env(PATRIMOINE_LONGITUDE)%',
        ],
    ]);
};
