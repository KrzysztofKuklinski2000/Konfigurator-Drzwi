<?php
require_once '../vendor/autoload.php';

use App\View;
use App\Core\Router;
use App\Core\Request;

$request = new Request();
$view = new View();

$router = new Router($request, $view);
$router->dispetch();