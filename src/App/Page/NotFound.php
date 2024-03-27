<?php

namespace Empira\App\Page;

use Empira\App\Page;
use Empira\Component\Header;
use Empira\Component\Header\Size;

class NotFound extends Page
{
    protected function init(): void
    {
        parent::init();

        $this->getApp()->setResponseStatusCode(404);

        $this->add(Header::create(
            "404 Not Found",
            Size::large()
        ));
    }
}