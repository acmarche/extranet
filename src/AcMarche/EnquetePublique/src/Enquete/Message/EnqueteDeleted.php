<?php

namespace AcMarche\EnquetePublique\Enquete\Message;

final readonly class EnqueteDeleted
{
    public function __construct(private int $enqueteId)
    {
    }

    public function getEnqueteId(): int
    {
        return $this->enqueteId;
    }
}
