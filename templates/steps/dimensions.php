<form method="POST" action="/?save-dimensions">
    <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md" >
        <div class="flex items-center gap-4 mb-4">
            <span class="block w-8 h-8 bg-blue-500 rounded "></span>
            <h2 class="uppercase text-xl">Podaj Wymary Drzwi</h2>
        </div>
        <input 
            type="number" name="width" 
            placeholder="Szerokość (cm)" 
            class="border border-gray-300 rounded p-2 mb-4 w-full"

        >
        <input 
            type="number" name="height" 
            placeholder="Wysokość (cm)" 
            class="border border-gray-300 rounded p-2 mb-4 w-full"
            >
    </div>

    <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md mt-8" >
        <div class="flex items-center gap-4 mb-4">
            <span class="block w-8 h-8 bg-blue-500 rounded "></span>
            <h2 class="uppercase text-xl">Kierunek otwierania</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            
            <?php foreach ($data['openingDirections'] as $direction): ?>
                <label class="cursor-pointer relative">
                    <input style="display:none" type="radio" name="opening_direction_id" value="<?= $direction['id'] ?>"  required>
                    
                    <div class="flex flex-col items-center gap-2 border border-gray-300 p-4 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-100 transition h-full justify-center text-center">
                        <p><?= $direction['nazwa'] ?></p>
                    </div>
                </label>
            <?php endforeach; ?>

        </div>
    </div>

    <button type="submit" class="p-4 bg-blue-500 rounded text-white font-bold mt-8 hover:bg-blue-600 transition">
        Dalej
    </button>
</form>
