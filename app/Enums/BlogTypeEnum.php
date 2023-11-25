<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;


class BlogTypeEnum extends Enum
{
    const DESTINATION   = "destinasi";
    const FESTIVAL      = "festival";
    const INSPIRATION   = "cerita-inspirasi";
    const TRIP          = "cerita-perjalanan";
    const CULINARY      = "kuliner";

    public static function toArray(): array
    {
        return [
            self::DESTINATION   => "destinasi",
            self::FESTIVAL      => "festival",
            self::INSPIRATION   => "cerita-inspirasi",
            self::TRIP          => "cerita-perjalanan",
            self::CULINARY      => "kuliner",
        ];
    }
}
