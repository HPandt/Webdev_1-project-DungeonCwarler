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

    // Admin routes
    $r->addRoute('GET', '/admin/dashboard', ['App\Controllers\AdminController', 'dashBoard']);
    $r->addRoute('GET', '/admin/characters', ['App\Controllers\AdminController', 'showCharacterTemplates']);
    $r->addRoute('GET', '/admin/characters/create', ['App\Controllers\AdminController', 'showCharacterForm']);
    $r->addRoute('POST', '/admin/characters/create', ['App\Controllers\AdminController', 'createCharacterTemplate']);
    $r->addRoute('GET', '/admin/characters/edit/{id:\d+}', ['App\Controllers\AdminController', 'editCharacterTemplate']);
    $r->addRoute('POST', '/admin/characters/update', ['App\Controllers\AdminController', 'updateCharacterTemplate']);
    $r->addRoute(['POST', 'GET'], '/admin/characters/delete/{id:\d+}', ['App\Controllers\AdminController', 'deleteCharacterTemplate']);
    // Room routes
    $r->addRoute('GET', '/admin/rooms', ['App\Controllers\AdminController', 'showRoomTemplates']);
    $r->addRoute('GET', '/admin/rooms/create', ['App\Controllers\AdminController', 'showRoomForm']);
    $r->addRoute('POST', '/admin/rooms/create', ['App\Controllers\AdminController', 'createRoomTemplate']);
    $r->addRoute('GET', '/admin/rooms/edit/{id:\d+}', ['App\Controllers\AdminController', 'editRoomTemplate']);
    $r->addRoute('POST', '/admin/rooms/update', ['App\Controllers\AdminController', 'updateRoomTemplate']);
    $r->addRoute(['POST','GET'], '/admin/rooms/delete/{id:\d+}', ['App\Controllers\AdminController', 'deleteRoom']);
    // Monster routes
    $r->addRoute('GET', '/admin/monsters', ['App\Controllers\AdminController', 'showMonsterTemplates']);
    $r->addRoute('GET', '/admin/monsters/create', ['App\Controllers\AdminController', 'showMonsterForm']);
    $r->addRoute('POST', '/admin/monsters/create', ['App\Controllers\AdminController', 'createMonsterTemplate']);
    $r->addRoute('GET', '/admin/monsters/edit/{id:\d+}', ['App\Controllers\AdminController', 'editMonsterTemplate']);
    $r->addRoute('POST', '/admin/monsters/update', ['App\Controllers\AdminController', 'updateMonsterTemplate']);
    $r->addRoute(['POST','GET'], '/admin/monsters/delete/{id:\d+}', ['App\Controllers\AdminController', 'deleteMonsterTemplate']);
    // User routes
    $r->addRoute('GET', '/admin/users', ['App\Controllers\AdminController', 'showUsers']);
    $r->addRoute('GET', '/admin/users/create', ['App\Controllers\AdminController', 'showUserForm']);
    $r->addRoute('POST', '/admin/users/create', ['App\Controllers\AdminController', 'createUser']);
    $r->addRoute('GET', '/admin/users/edit/{id:\d+}', ['App\Controllers\AdminController', 'editUser']);
    $r->addRoute('POST', '/admin/users/update', ['App\Controllers\AdminController', 'updateUser']);
    $r->addRoute(['POST','GET'], '/admin/users/delete/{id:\d+}', ['App\Controllers\AdminController', 'deleteUser']);


    //Api routes
    $r->addRoute('GET', '/api/game/start', ['App\Controllers\GameController', 'startDungeon']);
    $r->addRoute(['POST', 'GET'], '/api/game/choose-direction', ['App\Controllers\GameController', 'chooseDirection']);
    $r->addRoute(['POST','GET'], '/api/game/attack', ['App\Controllers\GameController', 'attack']);
    
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