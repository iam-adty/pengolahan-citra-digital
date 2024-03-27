<?php

namespace Empira\Component;

abstract class Property
{
    public function __construct(
        protected array $value = []
    ) {
    }

    protected function getValue(): mixed
    {
        return self::singleValue($this->value);
    }

    abstract protected function name(): string;

    public function toDefault(): array
    {
        return [
            $this->name() => $this->getValue()
        ];
    }

    /**
     * @param mixed ...$value
     * @return static
     */
    public static function value(...$value): static
    {
        return new static($value);
    }

    protected static function singleValue(array $value = []): mixed
    {
        return count($value) > 0 ? array_values($value)[0] : null;
    }

    protected static function multipleValue(): array
    {
        return [];
    }
}