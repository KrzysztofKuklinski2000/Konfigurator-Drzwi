<?php

namespace App\Controller;

use App\Core\Validator;
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
    private Validator $validator;
    public function __construct(Request $request, View $view, )
    {
        parent::__construct($request, $view);

        // Inicjalizacja repozytorium drzwi z połączeniem do bazy danych
        $this->doorRepository = new DoorRepository($this->getDbConnection());
        $this->orderService = new OrderService($request, $this->doorRepository);
        $this->validator = new Validator();
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
            $formData = $this->getDimensionsData();
            $errors = $this->validator->validateDimensions($formData);
            $order = array_merge($order, $formData);

            if(empty($errors)){
                $this->request->setSession('order', $order);
                header('Location: /model');
                exit();
            }
            $this->request->setSession('order', $order);
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
            $formData = $this->getModelData();
            $errors = $this->validator->validateModel($formData);

            if(empty($errors)) {
                $this->request->setSession('order', $order);
                header('Location: /wyposazenie');
                exit();
            }
            $this->request->setSession('order', $order);
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
        $this->orderService->checkAccess();

        $summaryData = $this->orderService->getSummaryData();

        $this->render('summary',[
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
        ]);
    }

    public function order(): void {
        try{
            $this->orderService->checkAccess();
            $order = $this->request->getSession('order');
            $errors = [];

            if($this->request->getMethod() === 'POST') {
                $formData = $this->getUserData();
                $errors = $this->validator->validateUserData($formData);
                $order = array_merge($order, $formData);
                
                if(empty($errors)) {
                    
                    $orderId = $this->orderService->saveOrder($order);
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
        $this->orderService->checkAccess();
        $order = $this->request->getSession('order') ?? [];
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

    private function getDimensionsData(): array 
    {
        return [
            'width' => (int) $this->request->getPost('width'),
            'height' => (int) $this->request->getPost('height'),
            'openingDirectionId' => (int) $this->request->getPost('openingDirectionId'),
        ];
    }

    private function getModelData(): array 
    {
        return [
            'colorId' => (int) $this->request->getPost('colorId'), 
            'typeId'  => (int) $this->request->getPost('typeId'),
        ];
    }

    private function getUserData(): array 
    {
        return [
            'deliveryMethodId' => (int) $this->request->getPost('deliveryMethodId'),
            'firstName'        => trim($this->request->getPost('firstName')),
            'lastName'         => trim($this->request->getPost('lastName')),
            'email'            => trim($this->request->getPost('email')),
            'phone'            => trim($this->request->getPost('phone')),
            'address'          => trim($this->request->getPost('address')),
            'postalCode'       => trim($this->request->getPost('postalCode')),
            'city'             => trim($this->request->getPost('city')),
        ];
    }
}