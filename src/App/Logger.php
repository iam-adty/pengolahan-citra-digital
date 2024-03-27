<?php

namespace Empira\App;

use Empira\Component\Property;

class Logger extends Property
{
    protected function name(): string
    {
        return 'logger';
    }

    /**
     * @param \Psr\Log\LoggerInterface $value
     * @return static
     */
    public static function value(...$value): static
    {
        return parent::value(...$value);
    }
}