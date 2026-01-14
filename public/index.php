<?php
require_once '../vendor/autoload.php';

use App\Controller\OrderController;
use App\View;
$get = $_GET;
$controller = new OrderController( new View());
$controller->dimensions();