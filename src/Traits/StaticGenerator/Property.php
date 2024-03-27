<?php

namespace Empira\Traits\StaticGenerator;

use Empira\Property\Defaults;

trait Property
{
    public static function property(...$properties): array
    {
        return Defaults::from($properties);
    }
}