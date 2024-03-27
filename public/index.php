<?php

use App\Properties;
use Atk4\Ui\Layout;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Empira\App;
use Empira\App\Page;
use Empira\App\Page\MethodNotAllowed;
use Empira\App\Page\NotFound;
use Kcs\ClassFinder\Finder\ComposerFinder;

use function FastRoute\simpleDispatcher;
use function Symfony\Component\String\u;

require_once __DIR__ . '/../vendor/autoload.php';

// exit(var_dump((new ComposerFinder())->subclassOf(Page::class)));

$dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
    foreach ((new ComposerFinder())->subclassOf(Page::class) as $pageClass => $pageReflection) {
        $lowerPageClass = strtolower($pageClass);

        $exPageClass = explode('\\', $lowerPageClass);

        if ($exPageClass[count($exPageClass) - 1] === 'index') {
            array_pop($exPageClass);
        }

        $uri = u(implode('/', $exPageClass))->trimPrefix('app/page')->toString();

        if ($uri === '') {
            $uri = '/';
        }

        $routeCollector->addRoute([
            'GET',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
            'OPTIONS',
            'HEAD'
        ], $uri, $pageClass);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

$page = null;

switch ($routeInfo[0]) {
    case Dispatcher::FOUND:
        $page = $routeInfo[1];
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $page = MethodNotAllowed::class;
        break;
    default:
    case Dispatcher::NOT_FOUND:
        $page = NotFound::class;
        break;
};

$app = App::create(...Properties::values());

if (is_subclass_of($page, Page::class)) {
    if (is_subclass_of($page::layout(), Layout::class, false)) {
        $app->initLayout($page::layout());
    } else {
        $app->initLayout([
            $page::layout()
        ]);
    }
}

$page::addTo($app);