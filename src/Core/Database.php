<?php

namespace App\Core;

use Exception;
use PDO;
use PDOException;

class Database {
    /**
     * Nawiązuje połączenie z bazą danych przy użyciu PDO.
     *
     * @return PDO
     * @throws Exception
     */
    public function connect(): PDO {
        // Wczytanie konfiguracji bazy danych
        $config = require __DIR__ . '/../../config/database.php';
        
        $dns = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

        try {
            // Nawiązanie połączenia z bazą danych przy użyciu PDO
            $pdo = new PDO($dns, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            return $pdo;
        } catch (PDOException $e) {
            // Obsługa błędu połączenia z bazą danych
            throw new Exception('Błąd przy połączeniu z bazą danych: '. $e->getMessage());
        }
    }
}