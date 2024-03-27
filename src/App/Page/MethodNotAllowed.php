<?php

namespace Empira\App\Page;

use Empira\App\Page;
use Empira\Component\Header;
use Empira\Component\Header\Size;

class MethodNotAllowed extends Page
{
    protected function init(): void
    {
        parent::init();

        $this->getApp()->setResponseStatusCode(405);

        $this->add(Header::create(
            "404 Method Not Allowed",
            Size::large()
        ));
    }
}