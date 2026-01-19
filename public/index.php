<?php
require_once '../vendor/autoload.php';

use App\View;
use App\Core\Router;
use App\Core\Request;

try {
    session_start();
    // unset($_SESSION['order']);
    $request = new Request();
    $view = new View();
    $router = new Router($request, $view);
    $router->dispetch();
}catch(Exception $e) {
    echo $e->getMessage();
}