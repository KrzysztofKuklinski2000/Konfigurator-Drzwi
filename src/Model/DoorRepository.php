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
            $stmt = $this->conn->prepare("SELECT * FROM akcesoria");
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

    /**
     * Pobiera listę dostępnych kolorów drzwi z bazy danych.
     *
     * @return array Lista kolorów.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */

    public function getColors(): array {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM kolory');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            throw new Exception('Błąd przy pobieraniu kolorów: '. $e->getMessage());
        }
    }

    /**
     * Pobiera listę dostępnych typów drzwi z bazy danych.
     *
     * @return array Lista typów drzwi.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */

    public function getTypes(): array {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM typy_drzwi');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            throw new Exception('Błąd przy pobieraniu typów drzwi: '. $e->getMessage());
        }
    }
    
    /**
     * Pobiera kolor na podstawie podanego identyfikatora.
     *
     * @param int $id Identyfikator koloru.
     * @return array|null Tablica koloru lub null, jeśli nie znaleziono.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */
    public function getColorById(int $id): ?array {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM kolory WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            throw new Exception('Błąd przy pobieraniu kolorów: '. $e->getMessage());
        }
    }

    /**
     * Pobiera typ drzwi na podstawie podanego identyfikatora.
     *
     * @param int $id Identyfikator typu drzwi.
     * @return array|null Tablica typu drzwi lub null, jeśli nie znaleziono.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */
    public function getTypeById(int $id): ?array {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM typy_drzwi WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            throw new Exception('Błąd przy pobieraniu typów drzwi: '. $e->getMessage());
        }
    }

    /**
     * Pobiera kierunek otwierania na podstawie podanego identyfikatora.
     *
     * @param int $id Identyfikator kierunku otwierania.
     * @return array|null Tablica kierunku otwierania lub null, jeśli nie znaleziono.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */
    public function getOpeningDirectionById(int $id): ?array {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM kierunek_otwierania WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            throw new Exception('Błąd przy pobieraniu kierunków otwierania: '. $e->getMessage());
        }
    }

    /**
     * Pobiera akcesoria na podstawie podanych identyfikatorów.
     *
     * @param array $ids Tablica identyfikatorów akcesoriów.
     * @return array|null Tablica akcesoriów lub null, jeśli nie znaleziono.
     * @throws Exception W przypadku błędu podczas pobierania danych.
     */
    public function getAccessoryByIds(array $ids): ?array {
        if(empty($ids)) {
            return [];
        }

        try {
            $in  = str_repeat('?,', count($ids)-1) . '?';
            $stmt = $this->conn->prepare("SELECT * FROM akcesoria WHERE id IN ($in)");
            $stmt->execute($ids);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            throw new Exception('Błąd przy pobieraniu akcesoriów: '. $e->getMessage());
        }
    }
}