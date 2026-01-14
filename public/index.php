<?php
require_once '../vendor/autoload.php';

use App\HomeController;
use App\View;

$controller = new HomeController( new View());
$controller->greet();