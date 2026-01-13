<?php
require_once '../vendor/autoload.php';

use App\Controller;

$controller = new Controller();
echo $controller->greet("World");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <title>Document</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="text-center">
        <h1 class="text-5xl font-bold text-red-600 mb-4">
            To działa!
        </h1>
        <p class="text-xl text-blue-500 bg-white p-6 rounded-lg shadow-lg">
            Jeśli to widzisz na kolorowo i ładnie, Tailwind śmiga.
        </p>
    </div>
</body>
</html>