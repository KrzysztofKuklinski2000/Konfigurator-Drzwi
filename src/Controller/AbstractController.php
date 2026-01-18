<?php

namespace App\Controller;

use PDO;
use Exception;
use App\Core\Request;
use App\Core\Database;
use App\View;

abstract class AbstractController
{
    /**
     * Połączenie z bazą danych.
     *
     * @var PDO
     */
    private PDO $dbConnection;

    /**
     * Konstruktor nawiązuje połączenie z bazą danych.
     *
     * @throws Exception
     */
    public function __construct(protected Request $request, protected View $view)
    {
        try {
             $this->dbConnection = (new Database())->connect();
        }catch(Exception $e){
            throw new Exception('Błąd przy połączeniu z bazą danych ' . $e->getMessage());
        }
    }
    
    /**
     * Renderuje widok z danymi.
     *
     * @param string $page
     * @param array $data
     * @return void
     */
    protected function render(string $page, $data = []): void
    {
        $order = $this->request->getSession('order') ?? [];    
        $fullData = array_merge(['order' => $order, 'page' => $page], $data);

        $this->view->render($fullData);
    }   

    /**
     * Zwraca połączenie z bazą danych.
     *
     * @return PDO
     */
    protected function getDbConnection(): PDO
    {
        return $this->dbConnection;
    }
}