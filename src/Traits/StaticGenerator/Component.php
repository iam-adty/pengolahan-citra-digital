<?php

namespace Empira\Traits\StaticGenerator;

use Empira\Property\Defaults;

trait Component
{
    public static function create(...$properties): static
    {
        return new static(Defaults::from($properties));
    }

    public static function seed(...$seed): array
    {
        return Defaults::from($seed);
    }
}