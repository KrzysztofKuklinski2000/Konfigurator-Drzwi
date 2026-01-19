<div class="flex flex-col md:flex-row gap-8 mt-8 items-start">
    <form action="/wyposazenie" method="POST" class="w-full md:w-2/3">
        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md" >
            <div class="flex items-center gap-4 mb-4">
                <span class="block w-8 h-8 bg-blue-500 rounded "></span>
                <h2 class="uppercase text-xl">Wyposazenie</h2>
            </div>
            <div class="flex flex-col gap-4">
                <?php foreach ($data['accessories'] as $accessory): ?>
                    <div class="flex items-center gap-4">
                        <input 
                            type="checkbox" 
                            name="accessories[]" 
                            value="<?= $accessory['id'] ?>" 
                            id="accessory-<?= $accessory['id'] ?>" 
                            class="w-4 h-4 peer"
                            <?= (isset($data['order']['accessories']) && in_array($accessory['id'], $data['order']['accessories'])) ? 'checked' : '' ?>
                        >
                        <label 
                            for="accessory-<?= $accessory['id'] ?>" 
                            class="cursor-pointer text-sm peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700"
                        >
                            <?= htmlspecialchars($accessory['nazwa']) ?>
                        </label>

                        <p class="text-xs">(+<?= htmlspecialchars($accessory['cena']) ?> zł)</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class=" cursor-pointer p-4 bg-blue-500 rounded text-white font-bold mt-8 hover:bg-blue-600 transition">
            Zapisz 
        </button>
    </form>

    <div class="w-full md:w-1/3 bg-white p-4 rounded-lg shadow-md border border-gray-300">
        <?php include dirname(__DIR__) . '/_partials/sidebar.php'; ?>
    </div>
</div>