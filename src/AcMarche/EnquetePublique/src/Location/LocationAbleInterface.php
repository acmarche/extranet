<?php

namespace AcMarche\EnquetePublique\Location;

interface LocationAbleInterface
{
    public function getRue(): ?string;

    public function setRue(string $rue);

    public function getNumero(): ?string;

    public function setNumero(?string $numero);

    public function getCodePostal(): ?int;

    public function setCodePostal(int $cp);

    public function getLocalite();

    public function setLocalite(string $localite);

    public function getLongitude(): ?string;

    public function setLongitude(?string $longitude);

    public function getLatitude(): ?string;

    public function setLatitude(?string $latitude);
}
