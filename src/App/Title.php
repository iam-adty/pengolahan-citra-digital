<?php

namespace Empira\App;

use Empira\Component\Property;

class Title extends Property
{
    protected function name(): string
    {
        return 'title';
    }

    protected function getValue(): mixed
    {
        return self::singleValue($this->value);
    }

    /**
     * @param string $value
     * @return static
     */
    public static function value(...$value): static
    {
        return parent::value(...$value);
    }
}