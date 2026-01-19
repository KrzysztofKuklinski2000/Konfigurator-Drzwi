<div class="flex flex-col md:flex-row gap-8 mt-8 items-start">
    <form action="/model" method="POST" class="w-full md:w-2/3">
        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md">
            <div class="flex items-center gap-4 mb-4">
                <span class="block w-8 h-8 bg-blue-500 rounded "></span>
                <h2 class="uppercase text-xl">Kolor drzwi</h2>
            </div>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-4 items-center gap-4">
                <?php foreach ($data['colors'] as $color): ?>
                    <label class="cursor-pointer relative">
                        <input 
                                style="display:none" 
                                type="radio" 
                                name="colorId" 
                                value="<?= $color['id'] ?>"  
                                class="peer"
                                <?= (isset($data['order']['colorId']) && $data['order']['colorId'] == $color['id']) ? 'checked' : '' ?> 
                            >
                        <div class="flex flex-col items-center gap-2 border border-gray-300 p-4 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                            <span style="background-color: <?= $color['kod_hex'] ?>" class="block w-16 h-16 rounded-full border "></span>
                            <p><?= htmlspecialchars($color['nazwa'])?></p>
                            <p class="text-xs"><?= $color['doplata'] > 0 ? '+'.htmlspecialchars($color['doplata']).' zł' : '' ?></p>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if(isset($data['errors']['color'])): ?>
                <p class="text-red-500 text-sm mt-2"><?= htmlspecialchars($data['errors']['color']) ?></p>
            <?php endif; ?>
        </div>

        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md mt-8">
            <div class="flex items-center gap-4 mb-4">
                <span class="block w-8 h-8 bg-blue-500 rounded "></span>
                <h2 class="uppercase text-xl">Wybierz Typ</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <?php foreach ($data['types'] as $type): ?>
                    <label class="cursor-pointer relative">
                        <input 
                            style="display:none" 
                            type="radio" 
                            name="typeId" 
                            value="<?= $type['id'] ?>"  
                            class="peer"
                            <?= (isset($data['order']['typeId']) && $data['order']['typeId'] == $type['id']) ? 'checked' : '' ?> 
                        >
                        
                        <div class="flex flex-col items-center gap-2 border border-gray-300 p-4 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-100 transition h-full justify-center text-center">
                            <p><?= htmlspecialchars($type['nazwa']) ?></p>
                            <p><?= htmlspecialchars($type['cena_bazowa']) ?> zł</p>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if(isset($data['errors']['type'])): ?>
                <p class="text-red-500 text-sm mt-2"><?= htmlspecialchars($data['errors']['type']) ?></p>
            <?php endif; ?>
        </div>
        <button type="submit" class=" cursor-pointer p-4 bg-blue-500 rounded text-white font-bold mt-8 hover:bg-blue-600 transition">
            Zapisz
        </button>
    </form>

    <div class="w-full md:w-1/3 bg-white p-4 rounded-lg shadow-md border border-gray-300">
        <?php include dirname(__DIR__) . '/_partials/sidebar.php'; ?>
    </div>
</div>