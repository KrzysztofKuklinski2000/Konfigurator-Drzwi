<?php

namespace App\Controller;

use App\Service\OrderService;
use App\View;
use Exception;
use Mpdf\Mpdf;
use App\Core\Request;
use App\Model\DoorRepository;

class OrderController extends AbstractController
{
    private DoorRepository $doorRepository;
    private OrderService $orderService;
    public function __construct(Request $request, View $view, )
    {
        parent::__construct($request, $view);

        // Inicjalizacja repozytorium drzwi z połączeniem do bazy danych
        $this->doorRepository = new DoorRepository($this->getDbConnection());
        $this->orderService = new OrderService($request, $this->doorRepository);
    }
    
    /**
     * Wyświetla widok dla wymiarów.
     *
     * @return void
     */
    public function dimensions(): void
    {
        $order = $this->request->getSession('order') ?? [];
        $errors = [];
        if($this->request->getMethod() === 'POST'){
            $order['width'] = (int) $this->request->getPost('width');
            $order['height'] = (int) $this->request->getPost('height');
            $order['openingDirectionId'] = (int) $this->request->getPost('openingDirectionId');
            
            // Walidacja danych
            if( $order['width'] <= 80 ||  $order['width'] >= 120){
                $errors['width'] = 'Szerokość musi być między 80 a 120 cm.';
            }

            if($order['height'] <= 200 || $order['height'] >= 250){
                $errors['height'] = 'Wysokość musi być między 200 a 250 cm.';
            }

            if(!$order['openingDirectionId']){
                $errors['openingDirection'] = 'Wybierz kierunek otwierania drzwi.';
            }

            // Jeśli brak błędów, zapisz dane do sesji i przekieruj do następnego kroku
            if(empty($errors)){
                $this->request->setSession('order', $order);
                header('Location: /model');
                exit();
            }
        }

        $summaryData = $this->orderService->getSummaryData();

        $this->render('dimensions', [
            'openingDirections' => $this->doorRepository->getOpeningDirection(),
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
            'errors' => $errors,
            'order' => $order
        ]);
    }

    /**
     * Wyświetla widok wyboru modelu drzwi.
     *
     * @return void
     */
    public function model(): void {
        $errors = [];
        $order = $this->request->getSession('order') ?? [];
        if($this->request->getMethod() === 'POST'){
            $order['colorId'] = $this->request->getPost('colorId');
            $order['typeId'] = $this->request->getPost('typeId');


            if(!$order['colorId']) $errors['color'] = "Wybierz kolor drzwi";
            if(!$order['typeId']) $errors['type'] = "Wybierz typ drzwi";

            if(empty($errors)) {
                $this->request->setSession('order', $order);
                header('Location: /wyposazenie');
                exit();

            }
        }

        $summaryData = $this->orderService->getSummaryData();

        $this->render('model', [
            'colors' => $this->doorRepository->getColors(),
            'types' => $this->doorRepository->getTypes(),
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
            'errors' => $errors,
            'order' => $order
        ]);
    }

    /**
     * Wyświetla widok wyboru wyposażenia drzwi.
     *
     * @return void
     */

    public function equipment(): void {
        $order = $this->request->getSession('order') ?? [];
        if($this->request->getMethod() === 'POST'){
            
            $order['accessories'] = $this->request->getPost('accessories') ?? [];


            $this->request->setSession('order', $order);
            header('Location: /podsumowanie');
            exit();
        }
        $summaryData = $this->orderService->getSummaryData();
        $this->render('equipment', [
            'accessories' => $this->doorRepository->getAccessories(),
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
            'order' => $order
        ]);
    }

    /**
     * Wyświetla widok podsumowania zamówienia.
     *
     * @return void
     */
    public function summary(): void {
        $order = $this->request->getSession('order') ?? [];

        $missingData = 
            empty($order['width']) || 
            empty($order['height']) || 
            empty($order['openingDirectionId']) || 
            empty($order['colorId']) || 
            empty($order['typeId']);

        if($missingData) {
            header('Location: /wymiary');
        }

        $summaryData = $this->orderService->getSummaryData();

        $this->render('summary',[
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
        ]);
    }

    public function order(): void {
        try{
            $order = $this->request->getSession('order') ?? [];

            $missingData = 
                empty($order['width']) || 
                empty($order['height']) || 
                empty($order['openingDirectionId']) || 
                empty($order['colorId']) || 
                empty($order['typeId']);

            if($missingData) {
                header('Location: /wymiary');
                exit();
            }

            $errors = [];

            if($this->request->getMethod() === 'POST') {
                $order['deliveryMethodId'] = (int) $this->request->getPost('deliveryMethodId');
                $order['firstName'] = trim($this->request->getPost('firstName'));
                $order['lastName']  = trim($this->request->getPost('lastName'));
                $order['email']     = trim($this->request->getPost('email'));
                $order['phone']     = trim($this->request->getPost('phone'));
                $order['address']   = trim($this->request->getPost('address'));
                $order['postalCode']= trim($this->request->getPost('postalCode'));
                $order['city']      = trim($this->request->getPost('city'));

                if(!$order['deliveryMethodId']) $errors['deliveryMethodId'] = 'Wybierz metodę dostawy';
                if(!$order['firstName']) $errors['firstName'] = 'Podaj imię';
                if(!$order['lastName'])  $errors['lastName'] = 'Podaj nazwisko';
                if(!$order['email'])     $errors['email'] = 'Podaj email';
                if(!$order['phone'])     $errors['phone'] = 'Podaj telefon';
                if(!$order['address'])   $errors['address'] = 'Podaj adres';
                if(!$order['postalCode'])$errors['postalCode'] = 'Podaj kod pocztowy';
                if(!$order['city'])      $errors['city'] = 'Podaj miasto';

                
                if(empty($errors)) {
                    $summaryData =$this->orderService->getSummaryData();
                    $productsPrice = $summaryData['summaryPrice'];

                    $deliveryMethods = $this->doorRepository->getDeliveryMethods();
                    $deliveryPrice = 0;
                    foreach($deliveryMethods as $deliveryMethod) {
                        if($deliveryMethod['id'] === $order['deliveryMethodId']) {
                            $deliveryPrice = $deliveryMethod['cena'];
                            break;
                        }
                    }

                    $finalePrice = ($productsPrice + $deliveryPrice);
                    $orderId = $this->doorRepository->saveOrder($order, $finalePrice, $deliveryPrice, $productsPrice);

                    $this->request->setSession('order', []);

                    header('Location: /dziekujemy?id=' . $orderId);
                    exit();
                }

                $this->request->setSession('order', $order);
            }

            $summaryData = $this->orderService->getSummaryData();

            $this->render('order', [
                'summaryData' => $summaryData['summaryDetails'],
                'summaryPrice' => $summaryData['summaryPrice'],
                'deliveryMethods' => $this->doorRepository->getDeliveryMethods(),
                'order' => $order,
                'errors' => $errors,
            ]);
        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function success(): void {
        $id = $this->request->getQuery('id') ?? 0;
        $this->render('success', ['orderId' => $id]);
    }

    public function pdf(): void {
        $order = $this->request->getSession('order') ?? [];

        $missingData = 
            empty($order['width']) || 
            empty($order['height']) || 
            empty($order['openingDirectionId']) || 
            empty($order['colorId']) || 
            empty($order['typeId']);

        if($missingData) {
            header('Location: /wymiary');
            exit();
        }

        $summaryData = $this->orderService->getSummaryData();
        $productsPrice = $summaryData['summaryPrice'];
        
        
        $deliveryPrice = 0;
        $deliveryName = "Nie wybrano";
        $deliveryMethods = $this->doorRepository->getDeliveryMethods();
        
        if (!empty($order['deliveryMethodId'])) {
            foreach($deliveryMethods as $deliveryMethod) {
                if($deliveryMethod['id'] == $order['deliveryMethodId']) {
                    $deliveryPrice = $deliveryMethod['cena'];
                    $deliveryName = $deliveryMethod['nazwa'];
                    break;
                }
            }
        }

        $totalPrice = $productsPrice + $deliveryPrice;

        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans; font-size: 12px; color: #333; }
                h1 { border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
                .box { background-color: #f3f4f6; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
                .total { font-size: 16px; font-weight: bold; color: #166534; text-align: right; padding-top: 20px;}
            </style>
        </head>
        <body>
            <h1>Oferta Konfiguracji Drzwi</h1>
            <p>Data wygenerowania: ' . date('Y-m-d H:i') . '</p>

            <div class="box">
                <h3>Specyfikacja Drzwi</h3>
                <table>
                    <tr><td>Wymiary:</td><td>' . $summaryData['summaryDetails']['width'] . ' x ' . $summaryData['summaryDetails']['height'] . ' cm</td></tr>
                    <tr><td>Model:</td><td>' . ($summaryData['summaryDetails']['type'] ?? '-') . '</td></tr>
                    <tr><td>Kolor:</td><td>' . ($summaryData['summaryDetails']['color'] ?? '-') . ' (Kod HEX)</td></tr>
                    <tr><td>Kierunek:</td><td>' . ($summaryData['summaryDetails']['openingDirection'] ?? '-') . '</td></tr>
                </table>
            </div>

            <div class="box">
                <h3>Wybrane Akcesoria</h3>
                <ul>';
                
        if (!empty($summaryData['summaryDetails']['accessories'])) {
            foreach ($summaryData['summaryDetails']['accessories'] as $acc) {
                $html .= '<li>' . $acc['nazwa'] . ' (' . number_format($acc['cena'], 2) . ' zł)</li>';
            }
        } else {
            $html .= '<li>Brak dodatkowych akcesoriów</li>';
        }

        $html .= '
                </ul>
            </div>

            <div class="box">
                <h3>Podsumowanie Kosztów</h3>
                <table>
                    <tr><td>Cena produktów:</td><td>' . number_format($productsPrice, 2) . ' zł</td></tr>
                    <tr><td>Dostawa (' . $deliveryName . '):</td><td>' . number_format($deliveryPrice, 2) . ' zł</td></tr>
                </table>
                <div class="total">DO ZAPŁATY: ' . number_format($totalPrice, 2) . ' zł</div>
            </div>
        </body>
        </html>';

        try {
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            
        } catch (\Mpdf\MpdfException $e) {
            echo "Błąd generowania PDF: " . $e->getMessage();
        }
    }
}