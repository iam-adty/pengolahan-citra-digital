<?php

namespace Empira\App;

use Atk4\Ui\View as UiView;
use Empira\Traits\StaticGenerator\Component;
use Empira\Traits\StaticGenerator\Defaults;
use Empira\Traits\StaticGenerator\Property;

/**
 * @method \Empira\App getApp()
 */
class View extends UiView
{
    use Component;
    use Property;
    use Defaults;

    protected function init(): void
    {
        parent::init();

        $this->build();
    }

    protected function build()
    {}
}