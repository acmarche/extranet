<?php

namespace AcMarche\Extranet\Security;

class LocatorRoles
{
    private iterable $handlers;

    public function __construct(
        iterable $handlers
    ) {
        $this->handlers = $handlers;
    }

    public function get(): iterable
    {
        return $this->handlers;
    }
}