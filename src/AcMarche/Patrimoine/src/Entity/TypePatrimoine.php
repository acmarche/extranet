<?php

namespace AcMarche\Patrimoine\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\Patrimoine\Repository\TypePatrimoineRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: TypePatrimoineRepository::class)]
#[ORM\Table(name: 'patrimoine_type')]
class TypePatrimoine implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    public ?string $nom = null;

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
