<?php

namespace App;

use Empira\App\Logger;
use Empira\App\TemplateDir;
use Empira\App\Title;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Properties
{
    public static function values(): array
    {
        return [
            Title::value('Pixel Color'),
            TemplateDir::value([
                __DIR__ . '/../template'
            ]),
            Logger::value(new MonologLogger(
                'app',
                [
                    new StreamHandler(
                        __DIR__ . '/../log/app.log',
                        Level::Debug
                    )
                ]
            )),
        ];
    }
}
