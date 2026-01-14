<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <title>Wycena</title>
</head>
<body class="bg-gray-100">
    <header class="flex justify-center items-center bg-blue-500 text-white text-2xl font-bold">
        <h1 class="text-2xl font-bold p-4">Konfigurator Drzwi Stalowych</h1>
    </header>
    <main class="w-full md:w-4/5 mx-auto p-4">
        <div class="text-center mt-8 bg-gray-200 p-6 rounded-lg shadow-md border border-gray-300">
            <h2 class="text-xl font-semibold mb-4">Witamy w naszym konfiguratorze drzwi stalowych!</h2>
            <p class="mb-4">Skonfiguruj swoje drzwi, wybierając spośród różnych opcji i dodatków.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2 mt-8">
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Podaj Wymiar</span>
                <span class="text-xl">1</span>
            </div>
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Wybierz Kolor</span>
                <span class="text-xl">2</span>
            </div>
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Rodzaj Otwerania</span>
                <span class="text-xl">3</span>
            </div>
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Rodzaj Przetłoczenoa</span>
                <span class="text-xl">4</span>
            </div>
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Rodzaj Ocieplenia</span>
                <span class="text-xl">5</span>
            </div>
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Dodatki</span>
                <span class="text-xl">6</span>
            </div>
            <div class="flex items-center justify-between border border-gray-300 rounded-xl p-3">
                <span class="text-xs">Sztuk</span>
                <span class="text-xl">7</span>
            </div>
        </div>
        <div>
            <?php 
                $page = $data['page'] ?? 'step_1';
                require_once dirname(__DIR__) . '/templates/steps/' . $page . '.php';
            ?>
        </div>
    </main>
</body>
</html>