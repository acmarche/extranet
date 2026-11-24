<?php

namespace AcMarche\Extranet\Security;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['marchebe.roles'])]
class SecurityRoles
{
    public function roles(): array
    {
        return ['ROLE_EXTRANET_ADMIN'];
    }
}
