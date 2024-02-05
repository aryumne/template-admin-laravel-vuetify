<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;


class OrderTypeEnum extends Enum
{
    const PACK  = "box";
    const PCS   = "pcs";

    public static function toArray(): array
    {
        return [
            self::PACK  => "box",
            self::PCS   => "pcs",
        ];
    }
}
