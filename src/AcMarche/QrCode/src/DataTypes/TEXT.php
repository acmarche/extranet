<?php

namespace AcMarche\QrCode\DataTypes;

use LaraZeus\QrCode\DataTypes\DataTypeInterface;

class TEXT implements DataTypeInterface
{
    /**
     * The prefix of the QrCode.
     */
    protected string $prefix = '';

    /**
     * The separator between the variables.
     */
    protected string $separator = ':';

    /**
     * The message.
     */
    protected string $message;

    /**
     * Generates the DataType Object and sets all of its properties.
     */
    public function create(array $arguments): void
    {
        $this->setProperties($arguments);
    }

    /**
     * Returns the correct QrCode format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->buildString();
    }

    /**
     * Sets the message for a message.
     */
    protected function setProperties(array $arguments): void
    {
        if (isset($arguments[0])) {
            $this->message = $arguments[0];
        }
    }

    /**
     * Builds a string.
     */
    protected function buildString(): string
    {
        return $this->prefix . $this->message;
    }
}
