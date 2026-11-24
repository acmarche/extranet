<?php

namespace AcMarche\Patrimoine\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\Patrimoine\Repository\StatutRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
#[ORM\Table(name: 'patrimoine_statut')]
class Statut implements Stringable
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
