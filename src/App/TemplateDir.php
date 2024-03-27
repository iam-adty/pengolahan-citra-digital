<?php

namespace Empira\App;

use Empira\Component\Property;

class TemplateDir extends Property
{
    protected function name(): string
    {
        return 'templateDir';
    }

    protected function getValue(): mixed
    {
        return self::singleValue($this->value);
    }

    /**
     * @param string|array $value
     * @return static
     */
    public static function value(...$value): static
    {
        return parent::value(...$value);
    }
}