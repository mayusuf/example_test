<?php 

require_once __DIR__.'/../vendor/autoload.php';

use App\Core\Application;

use App\Controller\UserController;
use App\Controller\ScooterController;
use App\Controller\TripController;

$rootDirectory = dirname(__DIR__);
$srcDirectory = $rootDirectory.'\src';

// Looing for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable($rootDirectory);
$dotenv->load();



$app = new Application($srcDirectory);

$app->router->post('/register', [new UserController(),'register']);
$app->router->post('/login', [new UserController(),'login']);
$app->router->post('/update', [new UserController(),'update']);
$app->router->delete('/delete', [new UserController(),'delete']);

$app->router->post('/sregister', [new ScooterController(),'register']);
$app->router->post('/slogin', [new ScooterController(),'login']);
$app->router->post('/supdate', [new ScooterController(),'update']);
$app->router->get('/status', [new ScooterController(),'getStatus']);
$app->router->get('/slogout', [new ScooterController(),'logout']);
$app->router->delete('/sdelete', [new ScooterController(),'delete']);


$app->router->post('/trip/create', [new TripController(),'create']);


$app->run();

?>