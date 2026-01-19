<?php

namespace App\Controller;

use App\View;
use Exception;
use App\Core\Request;
use App\Model\DoorRepository;

class OrderController extends AbstractController
{
    private DoorRepository $doorRepository;
    public function __construct(Request $request, View $view, )
    {
        parent::__construct($request, $view);

        // Inicjalizacja repozytorium drzwi z połączeniem do bazy danych
        $this->doorRepository = new DoorRepository($this->getDbConnection());
    }

    private function getSummaryData(): array {
        $order = $this->request->getSession('order') ?? [];

        $summaryData = [
            'width' => $order['width'] ?? null,
            'height' => $order['height'] ?? null,
            'openingDirection' => null,
            'color' => null,
            'type' => null,
            'accessories' => [],
        ];

        $summaryPrice = 0;

        if (isset($order['openingDirectionId'])) {
            $openingDirection = $this->doorRepository->getOpeningDirectionById($order['openingDirectionId']);
            $summaryData['openingDirection'] = $openingDirection ? $openingDirection['nazwa'] : null;
        }

        if (isset($order['colorId'])) {
            $color = $this->doorRepository->getColorById($order['colorId']);
            $summaryData['color'] = $color ? $color['kod_hex'] : null;
            $summaryPrice += $color ? $color['doplata'] : 0;
        }

        if (isset($order['typeId'])) {
            $type = $this->doorRepository->getTypeById($order['typeId']);
            $summaryData['type'] = $type ? $type['nazwa'] : null;
            $summaryPrice += $type ? $type['cena_bazowa'] : 0;
        }

        if (!empty($order['accessories'])) {
            $summaryData['accessories'] = $this->doorRepository->getAccessoryByIds($order['accessories']);
       
            foreach ($summaryData['accessories'] as $accessory) {
                $summaryPrice += $accessory['cena'];
            }
        }

        return ['summaryDetails' => $summaryData, 'summaryPrice' => $summaryPrice];
        
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

        $summaryData = $this->getSummaryData();

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

        $summaryData = $this->getSummaryData();

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
        $summaryData = $this->getSummaryData();
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

        $summaryData = $this->getSummaryData();

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
                    $summaryData = $this->getSummaryData();
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

            $summaryData = $this->getSummaryData();

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
}