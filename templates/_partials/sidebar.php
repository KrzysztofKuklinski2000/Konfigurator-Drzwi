<div>
    <h2 class="text-xl font-semibold text-gray-700">Twoja Konfiguracja</h2>
    <ul class="mt-4 space-y-2">
        <li class="border-b border-gray-200 pb-5 flex flex-col gap-2">
            <h3 class="text-gray-500 font-extralight text-sm">Wymiary Drzwi</h3>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Szerokość: </span>
                <span class="font-extralight text-sm"><?= $data['summaryData']['width'] ?? '-' ?> cm</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Wysokość: </span>
                <span class="font-extralight text-sm"><?= $data['summaryData']['height'] ?? '-' ?> cm</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Kierunek Otwierania: </span>
                <span class="font-extralight text-sm"><?= $data['summaryData']['openingDirection'] ?? '-' ?></span>
            </div>
        </li>
        <li class="border-b border-gray-200 pb-5 flex flex-col gap-2">
            <h3 class="text-gray-500 font-extralight text-sm">Model Drzwi</h3>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Kolor: </span>
                <span style="background-color: <?= $data['summaryData']['color'] ?? 'none' ?>" class="w-4 h-4 block rounded-full border"></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Typ: </span>
                <span class="font-extralight text-sm"><?= $data['summaryData']['type'] ?? '-' ?></span>
            </div>
        </li>
        <li class="border-b border-gray-200 pb-5 flex flex-col gap-2">
            <h3 class="text-gray-500 font-extralight text-sm">Wyposazenie</h3>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Akcesoria: </span>
                <div class="flex flex-col">
                    <?php foreach ($data['summaryData']['accessories'] ?? [] as $accessory): ?>
                        <span class="font-extralight text-sm">- <?= htmlspecialchars($accessory['nazwa']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </li>
        <li class="pb-5 flex flex-col gap-2">
            <h3 class="text-gray-500 font-extralight text-sm">Podsumowanie</h3>
            <div class="flex justify-between items-center">
                <span class="font-extralight text-sm">Cena: </span>
                <span class="font-extralight text-sm"><?= $data['summaryPrice'] ?> zł</span>
            </div>
        </li>
    </ul>
</div>