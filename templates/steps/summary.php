<div class="flex flex-col md:flex-row gap-8 mt-8 items-start">
    
    <div class="w-full md:w-2/3">
        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md">
            <div class="flex items-center gap-4 mb-6">
                <span class="block w-8 h-8 bg-blue-500 rounded"></span>
                <h2 class="uppercase text-xl font-bold">Twoja Konfiguracja</h2>
            </div>

            <div class="mb-6">
                <h3 class="font-bold text-gray-700 mb-2 border-b pb-1">1. Wymiary i Kierunek</h3>
                <div class="grid grid-cols-2 gap-4 text-sm mt-2">
                    <div>
                        <span class="text-gray-500">Szerokość:</span>
                        <span class="font-semibold block"><?= htmlspecialchars($data['summaryData']['width']) ?> cm</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Wysokość:</span>
                        <span class="font-semibold block"><?= htmlspecialchars($data['summaryData']['height']) ?> cm</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">Kierunek otwierania:</span>
                        <span class="font-semibold block"><?= htmlspecialchars($data['summaryData']['openingDirection']) ?></span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="font-bold text-gray-700 mb-2 border-b pb-1">2. Model Drzwi</h3>
                <div class="grid grid-cols-2 gap-4 text-sm mt-2">
                    <div>
                        <span class="text-gray-500">Typ drzwi:</span>
                        <span class="font-semibold block"><?= htmlspecialchars($data['summaryData']['type']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Kolor:</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-4 h-4 rounded-full border border-gray-300" style="background-color: <?= htmlspecialchars($data['summaryData']['color']) ?>"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-gray-700 mb-2 border-b pb-1">3. Wybrane Akcesoria</h3>
                <?php if (!empty($data['summaryData']['accessories'])): ?>
                    <ul class="space-y-2 text-sm mt-2">
                        <?php foreach ($data['summaryData']['accessories'] as $accessory): ?>
                            <li class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span><?= htmlspecialchars($accessory['nazwa']) ?></span>
                                <span class="font-semibold text-gray-600">+<?= number_format($accessory['cena'], 2, ',', ' ') ?> zł</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-sm text-gray-400 italic">Brak dodatkowych akcesoriów.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4 text-right">
            <a href="/wymiary" class="text-blue-500 hover:underline text-sm">Zmień konfigurację</a>
        </div>
    </div>

    <div class="w-full md:w-1/3">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300 sticky top-4">
            <h2 class="text-xl font-bold mb-4">Podsumowanie kosztów</h2>
            

            <div class="flex justify-between items-end mb-8">
                <span class="text-lg font-bold">DO ZAPŁATY:</span>
                <span class="text-xl font-bold text-blue-600">
                    <?= number_format($data['summaryPrice'], 2, ',', ' ') ?> zł
                </span>
            </div>

            <a href="/zamowienie" class="block w-full text-center p-4 bg-blue-500 text-white font-bold rounded">
                Przejdź do danych i dostawy
            </a>
        </div>
    </div>
</div>
