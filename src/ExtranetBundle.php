<?php

namespace AcMarche\Extranet;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExtranetBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
