<?php

use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrineConfig): void {
    $em = $doctrineConfig->orm()->entityManager('default');

    $em->mapping('AcMarcheExtranet')
        ->isBundle(false)
        ->type('attribute')
        ->dir('%kernel.project_dir%/src/AcMarche/Extranet/src/Entity')
        ->prefix('AcMarche\Extranet')
        ->alias('AcMarcheExtranet');
};
