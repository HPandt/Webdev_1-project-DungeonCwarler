<?php

/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 * 
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

/**
 * Define the routes for the application.
 */
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // $r->addRoute('GET', '/', ['App\Controllers\ArticleController', 'index']);
    // $r->addRoute('GET', '/api/articles', ['App\Controllers\ArticleController', 'apiGetAll']);
    // $r->addRoute('POST', '/api/articles', ['App\Controllers\ArticleController', 'apiCreate']);

    // Auth routes
    $r->addRoute('GET', '/', ['App\Controllers\AuthController', 'loginForm']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/register', ['App\Controllers\AuthController', 'registerForm']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);
    $r->addRoute('POST', '/logout', ['App\Controllers\AuthController', 'logout']);

    // Game routes
    $r->addRoute('GET', '/game/dashboard', ['App\Controllers\GameController', 'gameDashboard']);
    $r->addRoute('GET', '/game/start', ['App\Controllers\GameController', 'startMenu']);
    $r->addRoute('POST', '/game/startGame', ['App\Controllers\GameController', 'startGame']);
    $r->addRoute('GET', '/game/dungeon', ['App\Controllers\GameController', 'showDungeon']);

    //Api routes
    $r->addRoute('GET', '/api/game/start', ['App\Controllers\GameController', 'startDungeon']);
    //$r->addRoute('GET', '/api/game/choose-direction', ['App\Controllers\GameController', 'chooseDirection']);
    $r->addRoute('POST', '/api/game/choose-direction', ['App\Controllers\GameController', 'chooseDirection']);
    $r->addRoute('GET', '/api/game/attack', ['App\Controllers\GameController', 'attack']);
    
});


session_start();

/**
 * Get the request method and URI from the server variables and invoke the dispatcher.
 */
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

/**
 * Switch on the dispatcher result and call the appropriate controller method if found.
 */


switch ($routeInfo[0]) {
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    // Handle found routes
    case FastRoute\Dispatcher::FOUND:
        $class = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $controller = new $class();
        $vars = $routeInfo[2];
        $controller->$method($vars);
        break;
}