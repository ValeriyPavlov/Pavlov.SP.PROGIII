<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr7Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/MWToken.php';
require_once './middlewares/MWAdmin.php';
//require_once './middlewares/MWLog.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/LoginController.php';
require_once './controllers/CriptoMonedaController.php';
require_once './controllers/VentaController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('/', \UsuarioController::class . ':TraerTodos');
  $group->get('/{id}', \UsuarioController::class . ':TraerUno');
  $group->post('/', \UsuarioController::class . ':CargarUno')->add(new MWAdmin());
  $group->put('/', \UsuarioController::class . ':ModificarUno')->add(new MWAdmin());
  $group->delete('/', \UsuarioController::class . ':BorrarUno')->add(new MWAdmin());
})->add(new MWToken());

$app->group('/cripto', function (RouteCollectorProxy $group) {
  $group->get('/', \CriptoMonedaController::class . ':TraerTodos');
  $group->get('/{nacionalidad}', \CriptoMonedaController::class . ':TraerTodosPorNacionalidad');
  $group->get('/id/{id}', \CriptoMonedaController::class . ':TraerUno')->add(new MWToken());
  $group->post('/', \CriptoMonedaController::class . ':CargarUno')->add(new MWAdmin());
  $group->put('/', \CriptoMonedaController::class . ':ModificarUno')->add(new MWAdmin());
  $group->delete('/{id}', \CriptoMonedaController::class . ':BorrarUno')->add(new MWAdmin());
  $group->get('/csv/', \CriptoMonedaController::class . ':ExportarCripto');
});

$app->group('/ventas', function (RouteCollectorProxy $group){
  $group->post('/', \VentaController::class . ':CargarUno')->add(new MWToken());
  $group->get('/{nacionalidad}', \VentaController::class . ':TraerTodosPorNacionalidad')->add(new MWAdmin());
  $group->get('/moneda/{nombre}', \VentaController::class . ':TraerTodosPorNombre')->add(new MWAdmin());
});

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Segundo Parcial Programacion III"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/login', \LoginController::class . ':GenerarToken');
$app->get('/login', \LoginController::class . ':Deslogear');
//$app->delete('/test', \LoginController::class . ':Test')->add(new MWLog());

$app->run(); //php -S localhost:666 -t app
