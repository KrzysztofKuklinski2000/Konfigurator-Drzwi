-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sty 19, 2026 at 10:22 PM
-- Wersja serwera: 8.0.35
-- Wersja PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Konfigurator`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `akcesoria`
--

CREATE TABLE `akcesoria` (
  `id` int NOT NULL COMMENT 'Klucz główny',
  `nazwa` varchar(100) NOT NULL COMMENT 'Nazwa produktu',
  `cena` decimal(10,2) NOT NULL COMMENT 'Cena za produkt dodatkowy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `akcesoria`
--

INSERT INTO `akcesoria` (`id`, `nazwa`, `cena`) VALUES
(1, 'Zamek', 120.00),
(2, 'Bolec', 45.00),
(3, 'Przeszklenie', 350.00),
(4, 'Klamka', 60.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunek_otwierania`
--

CREATE TABLE `kierunek_otwierania` (
  `id` int NOT NULL COMMENT 'Klucz główny',
  `nazwa` varchar(100) NOT NULL COMMENT 'Lewe Otwieranie Do Wewnątrz'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kierunek_otwierania`
--

INSERT INTO `kierunek_otwierania` (`id`, `nazwa`) VALUES
(1, 'Prawe Otwieranie Do Wewnątrz'),
(2, 'Lewe Otwieranie Do Wewnątrz'),
(3, 'Prawe Otwieranie Na Zewnątrz'),
(4, 'Lewe Otwieranie Na Zewnątrz');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kolory`
--

CREATE TABLE `kolory` (
  `id` int NOT NULL COMMENT 'Klucz główny',
  `nazwa` varchar(50) NOT NULL COMMENT 'Nazwa koloru',
  `kod_hex` varchar(7) NOT NULL COMMENT 'Kod koloru np #FFFFFF',
  `doplata` decimal(10,2) NOT NULL COMMENT 'Kwota doliczana do ceny podstawowej'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kolory`
--

INSERT INTO `kolory` (`id`, `nazwa`, `kod_hex`, `doplata`) VALUES
(1, 'Biały', '#FFFFFF', 0.00),
(2, 'Czarny', '#000000', 150.00),
(3, 'Szary', '#6B7280', 150.00),
(4, 'Czerwony', '#EF4444', 250.00),
(5, 'Niebieski', '#3B82F6', 250.00),
(6, 'Fioletowy', '#8B5CF6', 300.00),
(7, 'Zielony', '#10B981', 300.00),
(8, 'Pomarańczowy', '#F97316', 300.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `metody_dostawy`
--

CREATE TABLE `metody_dostawy` (
  `id` int NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `cena` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `metody_dostawy`
--

INSERT INTO `metody_dostawy` (`id`, `nazwa`, `cena`) VALUES
(1, 'Odbiór osobisty', 0.00),
(2, 'Kurier DHL (Paleta)', 150.00),
(3, 'Transport Firmowy', 250.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typy_drzwi`
--

CREATE TABLE `typy_drzwi` (
  `id` int NOT NULL COMMENT 'Klucz główny',
  `nazwa` varchar(255) NOT NULL COMMENT 'Nazwa modelu np Premium Zewnętrzne',
  `cena_bazowa` decimal(10,2) NOT NULL COMMENT 'Cena podstawowa bez dodatków'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `typy_drzwi`
--

INSERT INTO `typy_drzwi` (`id`, `nazwa`, `cena_bazowa`) VALUES
(1, 'Podstawowe / Wewnętrzne', 1500.00),
(2, 'Premium / Wewnętrzne', 2200.00),
(3, 'Podstawowe / Zewnętrzne', 2600.00),
(4, 'Premium / Zewnętrzne', 3800.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id` int NOT NULL,
  `szerokosc` int NOT NULL,
  `wysokosc` int NOT NULL,
  `kierunek_id` int NOT NULL,
  `kolor_id` int NOT NULL,
  `typ_id` int NOT NULL,
  `metoda_dostawy_id` int NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `kod_pocztowy` varchar(10) NOT NULL,
  `miasto` varchar(100) NOT NULL,
  `cena_produktow` decimal(10,2) NOT NULL,
  `cena_dostawy` decimal(10,2) NOT NULL,
  `cena_calkowita` decimal(10,2) NOT NULL,
  `data_utworzenia` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id`, `szerokosc`, `wysokosc`, `kierunek_id`, `kolor_id`, `typ_id`, `metoda_dostawy_id`, `imie`, `nazwisko`, `email`, `telefon`, `adres`, `kod_pocztowy`, `miasto`, `cena_produktow`, `cena_dostawy`, `cena_calkowita`, `data_utworzenia`) VALUES
(1, 110, 220, 1, 2, 2, 3, 'asda', 'asdas', 'test@gmail.com', '123456789', 'Torowa', '213', 'Reda', 2745.00, 250.00, 2995.00, '2026-01-19 19:47:51'),
(2, 110, 230, 4, 7, 1, 1, 'Karol', 'Nowak', 'karol@gmail.com', '532221833', 'Towarowa 15', '84-240', 'Rumia', 2375.00, 0.00, 2375.00, '2026-01-19 19:50:05'),
(3, 110, 220, 1, 6, 2, 1, 'Karol', 'Nowak', 'karol@gmail.com', '21231234', 'wasdad', 'aaasd', 'asdasdsadsa', 2850.00, 0.00, 2850.00, '2026-01-19 23:01:02');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia_akcesoria`
--

CREATE TABLE `zamowienia_akcesoria` (
  `zamowienie_id` int NOT NULL,
  `akcesorium_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zamowienia_akcesoria`
--

INSERT INTO `zamowienia_akcesoria` (`zamowienie_id`, `akcesorium_id`) VALUES
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 3);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `akcesoria`
--
ALTER TABLE `akcesoria`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `kierunek_otwierania`
--
ALTER TABLE `kierunek_otwierania`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `kolory`
--
ALTER TABLE `kolory`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `metody_dostawy`
--
ALTER TABLE `metody_dostawy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `typy_drzwi`
--
ALTER TABLE `typy_drzwi`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metoda_dostawy_id` (`metoda_dostawy_id`);

--
-- Indeksy dla tabeli `zamowienia_akcesoria`
--
ALTER TABLE `zamowienia_akcesoria`
  ADD PRIMARY KEY (`zamowienie_id`,`akcesorium_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akcesoria`
--
ALTER TABLE `akcesoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Klucz główny', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kierunek_otwierania`
--
ALTER TABLE `kierunek_otwierania`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Klucz główny', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kolory`
--
ALTER TABLE `kolory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Klucz główny', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `metody_dostawy`
--
ALTER TABLE `metody_dostawy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `typy_drzwi`
--
ALTER TABLE `typy_drzwi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Klucz główny', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD CONSTRAINT `zamowienia_ibfk_1` FOREIGN KEY (`metoda_dostawy_id`) REFERENCES `metody_dostawy` (`id`);

--
-- Constraints for table `zamowienia_akcesoria`
--
ALTER TABLE `zamowienia_akcesoria`
  ADD CONSTRAINT `zamowienia_akcesoria_ibfk_1` FOREIGN KEY (`zamowienie_id`) REFERENCES `zamowienia` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
