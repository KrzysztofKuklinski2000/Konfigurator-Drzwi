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

### 3. Instalacja i kompilacja Frontend (Tailwind CSS)

```bash
    npm install
    npm run build
```

### 4. Baza Danych
1. Utwórz nową, pustą bazę danych (Konfigurator).
2. Zaimportuj do niej plik Database.sql.
    - phpMyAdmin: Wybierz bazę -> Importuj -> Wybierz plik.
    - Terminal: 
```bash 
    mysql -u root -p konfigurator < Database.sql
```

### 5.Konfiguracja połączenia
np w config/Database.php:
return [
    'host' => 'localhost',
    'dbname' => 'Konfigurator',
    'username' => 'root',
    'password' => 'root',
];