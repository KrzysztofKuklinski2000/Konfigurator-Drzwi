<?php

namespace App\Controller;

use PDO;
use App\Core\Database;
use Exception;

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
    public function __construct()
    {
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
    public function getDbConnection(): PDO
    {
        return $this->dbConnection;
    }
}