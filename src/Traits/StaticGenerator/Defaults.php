<?php

namespace Empira\Traits\StaticGenerator;

use Empira\Property\Defaults as PropertyDefaults;

trait Defaults
{
    public static function defaults(...$defaults): array
    {
        return PropertyDefaults::from($defaults);
    }
}