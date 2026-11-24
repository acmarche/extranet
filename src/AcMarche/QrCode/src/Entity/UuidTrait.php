<?php

namespace AcMarche\QrCode\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
    #[ORM\Column(type: Types::GUID, unique: true, nullable: false)]
    public ?string $uuid = null;

    public function generateUuid(): string
    {
        return Uuid::v4();
    }
}