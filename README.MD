# Konfigurator Drzwi

### 1. Pobranie projektu (Git)
Otwórz terminal w folderze, w którym chcesz umieścić projekt, i wykonaj polecenie klonowania:

```bash
git clone https://github.com/KrzysztofKuklinski2000/Konfigurator-Drzwi.git

cd nazwa-repozytorium
```

### 2. Backend (PHP)
W głównym katalogu projektu uruchom terminal i zainstaluj biblioteki PHP (mPDF):

```bash
composer install
```

DLA XAMPP TRZEBA W PLIKU  php.init (C:\xampp\php) odkomentować linijkę: ;extension=gd -> extension=gd
LUB 

```bash
composer install --ignore-platform-req=ext-gd
```

### 3. Instalacja i kompilacja Frontend (Tailwind CSS)

```bash
    npm install
    npm run dev
```

### 4. Baza Danych
1. Utwórz nową, pustą bazę danych (Konfigurator).
2. Zaimportuj do niej plik Database.sql.
    - phpMyAdmin: Wybierz bazę -> Importuj -> Wybierz plik.
    - Terminal: 
```bash 
    mysql -u root -p konfigurator < Konfigurator.sql
```

### 5.Konfiguracja połączenia
np w config/Database.php:
return [
    'host' => 'localhost',
    'dbname' => 'Konfigurator',
    'username' => 'root',
    'password' => 'root',
];

### 4. Odpalenie: 
Przechodzimy do katalogu głównego projektu i w termialu wpisujemy: 

```bash
    php -S localhost:8000 -t public
```