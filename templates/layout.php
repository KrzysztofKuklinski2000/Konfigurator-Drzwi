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
        <div class="js-links grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 mt-8 ">
    
            <a href="/wymiary" class="flex items-center justify-between border border-gray-300 hover:bg-gray-50 cursor-pointer rounded-xl p-3 transition">
                <span class="text-xs">Wymiary</span>
                <span class="text-xl">1</span>
            </a>

            <a href="/model" class="flex items-center justify-between border border-gray-300 hover:bg-gray-50 cursor-pointer rounded-xl p-3 transition">
                <span class="text-xs">Model</span>
                <span class="text-xl">2</span>
            </a>

            <a href="/wyposazenie" class="flex items-center justify-between border border-gray-300 hover:bg-gray-50 cursor-pointer rounded-xl p-3 transition">
                <span class="text-xs">Wyposażenie</span>
                <span class="text-xl">3</span>
            </a>

            <div class="flex items-center justify-between border border-gray-200 text-gray-400 bg-gray-50 rounded-xl p-3 cursor-default">
                <span class="text-xs">Podsumowanie</span>
                <span class="text-xl">4</span>
            </div>

            <div class="flex items-center justify-between border border-gray-200 text-gray-400 bg-gray-50 rounded-xl p-3 cursor-default">
                <span class="text-xs">Zamówienie</span>
                <span class="text-xl">5</span>
            </div>

        </div>
        <div class="mt-8" id ="content">
            <?php 
                $page = $data['page'] ?? 'equipment';
                require_once dirname(__DIR__) . '/templates/steps/' . $page . '.php';
            ?>
        </div>
    </main>
    <script src="/js/main.js?v=<?= time() ?>"></script>
</body>
</html>