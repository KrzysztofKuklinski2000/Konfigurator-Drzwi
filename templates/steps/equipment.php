 <form action="/wyposazenie" method="POST">
    <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md mt-8" >
        <div class="flex items-center gap-4 mb-4">
            <span class="block w-8 h-8 bg-blue-500 rounded "></span>
            <h2 class="uppercase text-xl">Wyposazenie</h2>
        </div>
        <div class="flex flex-col gap-4">
            <?php foreach ($data['accessories'] as $accessory): ?>
                <div class="flex items-center gap-4">
                    <input type="checkbox" name="accessories[]" value="<?= $accessory['id'] ?>" id="accessory-<?= $accessory['id'] ?>" class="w-4 h-4">
                    <label for="accessory-<?= $accessory['id'] ?>" class="cursor-pointer text-sm"><?= htmlspecialchars($accessory['nazwa']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <button type="submit" class=" cursor-pointer p-4 bg-blue-500 rounded text-white font-bold mt-8 hover:bg-blue-600 transition">
        Dalej
    </button>
</form>