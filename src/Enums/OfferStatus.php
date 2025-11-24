<?php

namespace IncadevUns\CoreDomain\Enums;

enum OfferStatus: string
{
    case Active = 'active';
    case Closed = 'closed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
