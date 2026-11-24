<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'paths' => ['%kernel.project_dir%/src/AcMarche/Taxe/templates' => 'Taxe'],
        'form_themes' => ['bootstrap_5_layout.html.twig'],
    ]);
};
