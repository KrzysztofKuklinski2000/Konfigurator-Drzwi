<?php 

namespace App\Model;

use PDO;
use Exception;
use PDOException;

class DoorRepository
{
    /**
     * Połączenie z bazą danych.
     *
     * @var PDO
     */
    private PDO $conn;

    /**
     * Konstruktor klasy DoorRepository.
     *
     * @param PDO $conn Połączenie z bazą danych.
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Pobiera listę dostępnych akcesoriów z bazy danych.
     *
     * @return array Lista akcesoriów.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */
    public function getAccessories(): array {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM accessories");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new Exception('Błąd przy pobieraniu akcesoriów: ' . $e->getMessage());
        }
    }

    /**
     * Pobiera listę dostępnych kierunków otwierania drzwi z bazy danych.
     *
     * @return array Lista kierunków otwierania.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */
    public function getOpeningDirection(): array {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM kierunek_otwierania");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new Exception('Błąd przy pobieraniu kierunków otwierania: ' . $e->getMessage());
        }
    }
}