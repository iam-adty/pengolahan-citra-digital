<?php

namespace Empira\Property;

use Empira\Component\Property;

class Defaults
{
    public static function from(array $properties): array
    {
        $defaults = [];

        foreach ($properties as $property) {
            if ($property instanceof Property) {
                $defaults = array_merge($defaults, $property->toDefault());
            } else {
                $defaults = array_merge($defaults, [$property]);
            }
        }

        return $defaults;
    }

    public static function values(...$properties): array
    {
        $defaults = [];

        foreach ($properties as $property) {
            if ($property instanceof Property) {
                $defaults = array_merge($defaults, $property->toDefault());
            } else {
                $defaults = array_merge($defaults, [$property]);
            }
        }

        return $defaults;
    }
}