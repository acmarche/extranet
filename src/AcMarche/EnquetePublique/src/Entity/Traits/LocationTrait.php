<?php

namespace AcMarche\EnquetePublique\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait LocationTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $rue = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $numero = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int|null $code_postal = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $localite = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $longitude = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $latitude = null;

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): void
    {
        $this->code_postal = $code_postal;
    }

    public function getLocalite(): ?string
    {
        return $this->localite;
    }

    public function setLocalite(?string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }
}
