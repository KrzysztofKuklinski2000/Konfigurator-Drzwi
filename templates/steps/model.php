<form action="/?save-model" method="POST">
    <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md mt-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="block w-8 h-8 bg-blue-500 rounded "></span>
            <h2 class="uppercase text-xl">Kolor drzwi</h2>
        </div>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 items-center gap-4">
            <?php foreach ($data['colors'] as $color): ?>
                <label class="cursor-pointer relative">
                    <div class="flex flex-col items-center gap-2 border border-gray-300 p-4 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input style="display:none" type="radio" name="door_color" value="<?= $color['id'] ?>"  required>
                        <span style="background-color: <?= $color['kod_hex'] ?>" class="block w-16 h-16 rounded-full border"></span>
                        <p><?= htmlspecialchars($color['nazwa'])?></p>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md mt-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="block w-8 h-8 bg-blue-500 rounded "></span>
            <h2 class="uppercase text-xl">Wybierz Typ</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <?php foreach ($data['types'] as $type): ?>
                <label class="cursor-pointer relative">
                    <input style="display:none" type="radio" name="type_id" value="<?= $type['id'] ?>"  required>
                    
                    <div class="flex flex-col items-center gap-2 border border-gray-300 p-4 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-100 transition h-full justify-center text-center">
                        <p><?= htmlspecialchars($type['nazwa']) ?></p>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <button type="submit" class=" cursor-pointer p-4 bg-blue-500 rounded text-white font-bold mt-8 hover:bg-blue-600 transition">
        Dalej
    </button>
</form>