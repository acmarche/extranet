<?php

namespace AcMarche\Extranet\Security;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class LocatorRoles
{
    public function __construct(
        #[Autowire([
            new AutowireLocator('marchebe.roles'),
        ])]
        private readonly iterable $handlers,
    ) {}

    public function get(): iterable
    {
        return $this->handlers;
    }
}
