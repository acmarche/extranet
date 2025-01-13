<?php

namespace AcMarche\Extranet\Security;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class LocatorRoles
{
    public function __construct( #[Autowire([
             new TaggedIterator('marchebe.roles'),
       ] )]
    private readonly iterable $handlers)
    {
    }

    public function get(): iterable
    {
        return $this->handlers;
    }
}
