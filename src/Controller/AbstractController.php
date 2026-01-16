<?php

namespace App\Controller;

use PDO;
use Exception;
use App\Core\Request;
use App\Core\Database;

abstract class AbstractController
{
    /**
     * Połączenie z bazą danych.
     *
     * @var PDO
     */
    private PDO $dbConnection;

    /**
     * Obiekt żądania HTTP.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Konstruktor nawiązuje połączenie z bazą danych.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->request = new Request();

        try {
             $this->dbConnection = (new Database())->connect();
        }catch(Exception $e){
            throw new Exception(message: 'Błąd przy połączeniu z bazą danych ' . $e->getMessage());
        }
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