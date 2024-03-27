<?php

namespace Empira\Component\Header;

use Empira\Component\Property;

class Size extends Property
{
    protected function name(): string
    {
        return 'size';
    }

    protected function getValue(): mixed
    {
        return self::singleValue($this->value);
    }

    /**
     * @param int|string $value 1-6 or small, medium, large
     * @return static
     */
    public static function value(...$value): static
    {
        return parent::value(...$value);
    }

    public static function large(): static
    {
        return self::value('large');
    }

    public static function medium(): static
    {
        return self::value('medium');
    }

    public static function small(): static
    {
        return self::value('small');
    }
}