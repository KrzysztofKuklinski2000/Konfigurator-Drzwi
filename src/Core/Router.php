<?php

namespace App\Core;

use App\View;
use App\Controller\OrderController;

class Router {


    public function __construct(private Request $request, private View $view){}

    /*
    * Główna metoda routingu, która przekierowuje żądania do odpowiednich kontrolerów na podstawie URI.
    *
    * @return void
    */
    public function dispetch(): void {
        $path = $this->request->getUri();

        $orderController = new OrderController($this->view);

        match($path) {
            '/', '/wymiary' => $orderController->dimensions(),
            '/model' => $orderController->model(),
            '/wyposazenie' => $orderController->equipment(),
            '/podsumowanie' => $orderController->summary(),
            default => print("404 - Nie znaleziono"),
        };
    }
}