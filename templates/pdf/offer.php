<html>
    <head>
        <style>
            body { 
                font-family: DejaVu Sans; 
                font-size: 12px; 
                color: #333; 
            }

            h1 { 
                border-bottom: 2px solid #3b82f6; 
                padding-bottom: 10px; 
            }
            .box { 
                background-color: #f3f4f6; 
                padding: 10px; 
                margin-bottom: 20px; 
                border: 1px solid #ddd; 
            }
            table {
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 10px; 
            }
            th, td {
                border-bottom: 1px solid #ddd; 
                padding: 8px; 
                text-align: left; 
            }

            .total { 
                font-size: 16px; 
                font-weight: bold; 
                color: #166534; 
                text-align: right; 
                padding-top: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Oferta Konfiguracji Drzwi</h1>
        <p>Data wygenerowania: <?=   date('Y-m-d H:i') ?></p>

        <div class="box">
            <h3>Specyfikacja Drzwi</h3>
            <table>
                <tr>
                    <td>Wymiary:</td>
                    <td><?= $data['summaryDetails']['width'] ?> x <?= $data['summaryDetails']['height'] ?> cm</td>
                </tr>
                <tr>
                    <td>Model:</td>
                    <td>(<?=  $data['summaryDetails']['type'] ?? '-' ?>)</td>
                </tr>
                <tr>
                    <td>Kolor:</td>
                    <td>(<?= $data['summaryDetails']['color'] ?? '-' ?>)(Kod HEX)</td>
                </tr>
                <tr>
                    <td>Kierunek:</td>
                    <td>(<?=  $data['summaryDetails']['openingDirection'] ?? '-' ?>)</td>
                </tr>
            </table>
        </div>

        <div class="box">
            <h3>Wybrane Akcesoria</h3>
            <ul>
                <?php if(!empty($data['summaryDetails']['accessories'])): ?>
                    <?php foreach($data['summaryDetails']['accessories'] as $acc): ?>
                        <li> <?= $acc['nazwa'] ?><?= number_format($acc['cena'], 2) ?> zł</li>
                    <?php endforeach ?>
                <?php else: ?>
                    <li>Brak dodatkowych akcesoriów</li>
                <?php endif ?>
            </ul>
        </div>

        <div class="box">
            <h3>Podsumowanie Kosztów</h3>
            <table>
                <tr>
                    <td>Cena produktów:</td>
                    <td><?= number_format($data['productsPrice'], 2)?> zł</td>
                </tr>
                <tr>
                    <td>Dostawa (<?=  $data['deliveryName'] ?>):</td>
                    <td><?= number_format($data['deliveryPrice'], 2) ?> zł</td>
                </tr>
            </table>
            <div class="total">DO ZAPŁATY: ' . <?= number_format($data['totalPrice'], 2) ?> zł</div>
        </div>
    </body>
</html>';