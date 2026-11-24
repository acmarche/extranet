<?php

namespace AcMarche\EnquetePublique\Enquete\Message;

class EnqueteUpdated
{
    public function __construct(private readonly int $enqueteId, private readonly ?string $oldRue)
    {
    }

    public function getEnqueteId(): int
    {
        return $this->enqueteId;
    }

    public function getOldRue(): ?string
    {
        return $this->oldRue;
    }
}
