<?php

namespace AcMarche\QrCode\Constant;

enum QrTypeEnum: string
{
    case SMS = 'sms';
    case PHONE_NUMBER = 'phoneNumber';
    case EMAIL = 'email';
    case EPC = 'epc';
    case WIFI = 'wifi';
    case GEO = 'geo';
    case URL = 'url';
    case TEXT = 'text';

    public function getLabel(): string
    {
        return match ($this) {
            self::SMS => 'Sms',
            self::PHONE_NUMBER => 'Numéro de téléphone',
            self::EMAIL => 'Email',
            self::WIFI => 'Wifi',
            self::URL => 'Url',
            self::TEXT => 'Texte',
            self::GEO => 'Geo',
            self::EPC => 'Virement bancaire',
        };
    }
}
