<?php

$loader = require __DIR__ . '/vendor/autoload.php';

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

/**
 * Define routes
 */
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/hello', 'sayHello');
});

/**
 * Configure container
 */

$container = new \Pimple\Container();

$container['event'] = function($c) use ($argv) {
    return json_decode($argv[1], true) ?: [];
};

$container['context'] = function($c) use ($argv) {
    return json_decode($argv[2], true) ?: [];
};

$container['logger'] = function($c) {
    $logger = new Logger('handler');
    $logger->pushHandler(new ErrorLogHandler());

    return $logger;
};

$container['sayHello'] = function($c) {
    return new App\SayHello();
};

/**
 * Handle request
 */
$event = $container['event'];
$routeInfo = $dispatcher->dispatch($event['httpMethod'], $event['path']);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $container['logger']->error('404 Not found', $event);
        printf(json_encode([
            'statusCode' => 404,
            'body' => json_encode(['error' => 'Not found']),
        ]));
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        $container['logger']->error(sprintf('405 Method not allowed, allowed: %s', implode(', ', $allowedMethods)), $event);
        printf(json_encode([
            'statusCode' => 405,
            'body' => json_encode(['error' => sprintf('405 Method not allowed, allowed: %s', implode(', ', $allowedMethods))]),
        ]));
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container['logger']->info(sprintf('Matched route %s', $handler));
        call_user_func_array($container[$handler], $vars);
        break;
}
