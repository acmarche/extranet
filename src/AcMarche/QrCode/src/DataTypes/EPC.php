<?php

namespace AcMarche\QrCode\DataTypes;

use LaraZeus\QrCode\DataTypes\DataTypeInterface;


class EPC implements DataTypeInterface
{
    /**
     * The separator between the variables.
     */
    protected string $separator = "\n";

    protected string $prefix = "BCD";

    protected string $version = "001";

    protected int $character = 1;//1 UTF-8

    protected string $SCT = "SCT";

    protected string $iban = " ";

    protected string $recipient = " ";

    protected string $currency = "EUR";

    protected string $amount = " ";

    protected string $message = " ";

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
     * Sets the phone number and message for a sms message.
     */
    protected function setProperties(array $arguments): void
    {
        if (isset($arguments['currency'])) {
            $this->amount = $arguments['currency'];
        }

        if (isset($arguments['amount'])) {
            $this->amount = $arguments['amount'];
        }

        if (isset($arguments['iban'])) {
            $this->iban = $arguments['iban'];
        }

        if (isset($arguments['message'])) {
            $this->message = $arguments['message'];
        }

        if (isset($arguments['recipient'])) {
            $this->recipient = $arguments['recipient'];
        }

        if (isset($arguments['message'])) {
            $this->message = $arguments['message'];
        }
    }

    /**
     * Builds a SEPA Credit Transfer (SCT) string.
     */
    protected function buildString(): string
    {
        return $this->prefix
            .$this->separator
            .$this->version
            .$this->separator
            .$this->character
            .$this->separator
            .$this->SCT
            .$this->separator
            .$this->iban
            .$this->separator
            .$this->recipient
            .$this->separator
            .$this->currency
            .$this->separator
            .$this->amount
            .$this->separator
            .$this->message;
    }
}