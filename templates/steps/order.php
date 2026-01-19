<form action="/zamowienie" method="POST" class="flex flex-col md:flex-row gap-8 mt-8 items-start">
    
    <div class="w-full md:w-2/3 space-y-6">
        
        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md">
            <div class="flex items-center gap-4 mb-4">
                <span class="w-8 h-8 bg-blue-500 rounded text-white flex items-center justify-center font-bold">1</span>
                <h2 class="uppercase text-xl font-bold">Sposób Dostawy</h2>
            </div>
            
            <div class="space-y-3">
                <?php foreach ($data['deliveryMethods'] as $method): ?>
                    <label class="flex items-center justify-between border border-gray-200 p-4 rounded-lg cursor-pointer hover:bg-blue-50 transition has-checked:border-blue-500 has-checked:bg-blue-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="deliveryMethodId" value="<?= $method['id'] ?>" 
                                class="w-5 h-5 text-blue-600"
                                <?= (isset($data['order']['deliveryMethodId']) && $data['order']['deliveryMethodId'] == $method['id']) ? 'checked' : '' ?>
                            >
                            <span class="font-medium"><?= htmlspecialchars($method['nazwa']) ?></span>
                        </div>
                        <span class="font-bold text-gray-700">
                            <?= $method['cena'] > 0 ? htmlspecialchars($method['cena']) . ' zł' : '0,00 zł' ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if(isset($data['errors']['deliveryMethodId'])): ?>
                <p class="text-red-500 text-sm mt-2 font-bold"><?= $data['errors']['deliveryMethodId'] ?></p>
            <?php endif; ?>
        </div>

        <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md">
            <div class="flex items-center gap-4 mb-4">
                <span class="w-8 h-8 bg-blue-500 rounded text-white flex items-center justify-center font-bold">2</span>
                <h2 class="uppercase text-xl font-bold">Dane Zamawiającego</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imię</label>
                    <input type="text" name="firstName" value="<?= $data['order']['firstName'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['firstName'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['firstName'] ?></p><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nazwisko</label>
                    <input type="text" name="lastName" value="<?= $data['order']['lastName'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['lastName'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['lastName'] ?></p><?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="<?= $data['order']['email'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['email'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['email'] ?></p><?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="phone" value="<?= $data['order']['phone'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['phone'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['phone'] ?></p><?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ulica i numer domu/mieszkania</label>
                    <input type="text" name="address" value="<?= $data['order']['address'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['address'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['address'] ?></p><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kod pocztowy</label>
                    <input type="text" name="postalCode" value="<?= $data['order']['postalCode'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['postalCode'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['postalCode'] ?></p><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Miasto</label>
                    <input type="text" name="city" value="<?= $data['order']['city'] ?? '' ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php if(isset($data['errors']['city'])): ?><p class="text-red-500 text-xs mt-1"><?= $data['errors']['city'] ?></p><?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="text-right">
             <a href="/generuj-pdf" target="_blank" class="text-blue-500 underline text-sm hover:text-blue-700">Pobierz ofertę w PDF</a>
        </div>

    </div>

    <div class="w-full md:w-1/3 bg-white p-6 rounded-lg shadow-md border border-gray-300 sticky top-4">
        <h2 class="text-xl font-bold mb-4 border-b pb-2">Do zapłaty</h2>
        
        <div class="space-y-2 text-gray-600 mb-6 text-sm">
            <div class="flex justify-between">
                <span>Konfiguracja drzwi:</span>
                <span class="font-semibold"><?= number_format($data['summaryPrice'], 2, ',', ' ') ?> zł</span>
            </div>
            
            <div class="flex justify-between text-blue-600">
                <span>Dostawa:</span>
                <span class="font-semibold">
                    <?php 
                        $deliveryCost = 0;
                        if(isset($data['order']['deliveryMethodId'])) {
                            foreach($data['deliveryMethods'] as $deliveryMethod) {
                                if($deliveryMethod['id'] == $data['order']['deliveryMethodId']) $deliveryCost = $deliveryMethod['cena'];
                            }
                        }
                        echo number_format($deliveryCost, 2, ',', ' ') . ' zł';
                    ?>
                </span>
            </div>
        </div>

        <div class="flex justify-between items-end mb-8 bg-gray-50 p-4 rounded border border-gray-200">
            <span class="text-lg font-bold">RAZEM:</span>
            <span class="text-3xl font-bold text-green-600">
                <?= number_format(($data['summaryPrice'] + $deliveryCost), 2, ',', ' ') ?> zł
            </span>
        </div>

        <button type="submit" name="submitOrder" class="w-full p-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg text-lg transition shadow-lg transform hover:-translate-y-0.5">
            ZAMAWIAM I PŁACĘ
        </button>
    </div>
</form>