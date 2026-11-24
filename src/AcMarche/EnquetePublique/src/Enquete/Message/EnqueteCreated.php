<?php

namespace AcMarche\EnquetePublique\Enquete\Message;

class EnqueteCreated
{
    public function __construct(private readonly int $enqueteId)
    {
    }

    public function getEnqueteId(): int
    {
        return $this->enqueteId;
    }
}
