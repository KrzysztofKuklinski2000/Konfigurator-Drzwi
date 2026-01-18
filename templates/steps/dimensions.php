<div class="flex flex-col md:flex-row gap-8 mt-8 items-start">
    
    <form method="POST" action="/wymiary" class="w-full md:w-2/3">
        
        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md">
            <div class="flex items-center gap-4 mb-4">
                <span class="block w-8 h-8 bg-blue-500 rounded"></span>
                <h2 class="uppercase text-xl">Podaj Wymiary Drzwi</h2>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <input 
                        type="number" 
                        name="width" 
                        placeholder="Szerokość" 
                        value="<?= htmlspecialchars($data['order']['width'] ?? '') ?>"
                        class="border border-gray-300 rounded p-2 pr-10 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none">
                        cm
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <input 
                        type="number" 
                        name="height" 
                        placeholder="Wysokość" 
                        value="<?= htmlspecialchars($data['order']['height'] ?? '') ?>"
                        class="border border-gray-300 rounded p-2 pr-10 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none">
                        cm
                    </span>
                </div>
            </div>

        </div>

        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md mt-8">
            <div class="flex items-center gap-4 mb-4">
                <span class="block w-8 h-8 bg-blue-500 rounded"></span>
                <h2 class="uppercase text-xl">Kierunek otwierania</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                
                <?php foreach ($data['openingDirections'] as $direction): ?>
                    <label class="cursor-pointer relative group">
                        <input 
                            style="display:none" 
                            type="radio" 
                            name="opening_direction_id" 
                            value="<?= $direction['id'] ?>"  
                            required 
                            <?= (isset($data['order']['openingDirection']) && $data['order']['openingDirection'] == $direction['id']) ? 'checked' : '' ?> 
                            class="peer"
                        >
                        
                        <div class="flex flex-col items-center gap-2 border border-gray-300 p-4 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-100 transition h-full justify-center text-center">
                            <p><?= htmlspecialchars($direction['nazwa'])?></p>
                        </div>
                    </label>
                <?php endforeach; ?>

            </div>
        </div>

        <button type="submit" class="p-4 bg-blue-500 rounded text-white font-bold mt-8 hover:bg-blue-600 transition shadow-lg w-full md:w-auto">
            Dalej
        </button>
    </form>

    <div class="w-full md:w-1/3 bg-white p-4 rounded-lg shadow-md border border-gray-300">
        <?php include dirname(__DIR__) . '/_partials/sidebar.php'; ?>
    </div>
</div>