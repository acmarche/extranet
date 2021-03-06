<?php

namespace AcMarche\Extranet\Security;

class LocatorRoles
{
    public function __construct(private iterable $handlers)
    {
    }

    public function get(): iterable
    {
        return $this->handlers;
    }
}
