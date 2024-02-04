<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;


class OrderTypeEnum extends Enum
{
    const PACK  = "pack";
    const PCS   = "pcs";

    public static function toArray(): array
    {
        return [
            self::PACK  => "pack",
            self::PCS   => "pcs",
        ];
    }
}
