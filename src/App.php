<?php

namespace Empira;

use Atk4\Core\InitializerTrait;
use Atk4\Ui\App as UiApp;
use Empira\Traits\StaticGenerator\Component;

class App extends UiApp
{
    use Component;
    use InitializerTrait;

    public $cdn = [
        'atk' => 'assets/vendor/atk4/ui',
        'jquery' => 'assets/vendor/atk4/ui/external/jquery/dist',
        'fomantic-ui' => 'assets/vendor/atk4/ui/external/fomantic-ui/dist',
        'flatpickr' => 'assets/vendor/atk4/ui/external/flatpickr/dist',
        'highlight.js' => 'assets/vendor/atk4/ui/external/@highlightjs/cdn-assets',
        'chart.js' => 'assets/vendor/atk4/ui/external/chart.js/dist',
    ];

    protected string $urlBuildingIndexPage = '';

    protected string $urlBuildingExt = '';

    protected string $host = '';

    public function __construct(
        array $defaults = []
    )
    {
        parent::__construct($defaults);

        $this->init();
    }

    protected function init(): void
    {
        $this->host = $this->getRequest()->getServerParams()['HTTP_HOST'];

        if (isset($this->layout)) {
            $this->initLayout($this->layout);
        }
    }

    public function getHost(): string {
        return $this->host;
    }
}