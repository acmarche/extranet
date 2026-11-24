<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('liip_imagine', ['resolvers' => ['default' => ['web_path' => null]]]);

    $containerConfigurator->extension(
        'liip_imagine',
        [
            'filter_sets' => [
                'cache' => null,
                'my_thumb' => [
                    'quality' => 95,
                    'filters' => ['thumbnail' => ['size' => [120, 45], 'mode' => 'inset']],
                ],
                'miniature' => [
                    'quality' => 95,
                    'filters' => ['thumbnail' => ['size' => [240, 180], 'mode' => 'outbound']],
                ],
                'patrimoine_thumb' => [
                    'quality' => 95,
                    'filters' => ['thumbnail' => ['size' => [250, 188], 'mode' => 'inset']],
                ],
                'my_heighten_filter' => [
                    'quality' => 95,
                    'filters' => ['relative_resize' => ['heighten' => 120]],
                ],
            ],
        ]
    );
};

