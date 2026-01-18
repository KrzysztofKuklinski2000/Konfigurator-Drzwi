<?php

namespace App\Controller;

use App\View;
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

        if (isset($order['color'])) {
            $color = $this->doorRepository->getColorById($order['color']);
            $summaryData['color'] = $color ? $color['kod_hex'] : null;
            $summaryPrice += $color ? $color['doplata'] : 0;
        }

        if (isset($order['type'])) {
            $type = $this->doorRepository->getTypeById($order['type']);
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
        $errors = [];
        if($this->request->getMethod() === 'POST'){
            $width = (int) $this->request->getPost('width');
            $height = (int) $this->request->getPost('height');
            $openingDirectionId = (int) $this->request->getPost('openingDirectionId');
            
            // Walidacja danych
            if($width <= 80 || $width >= 120){
                $errors['width'] = 'Szerokość musi być między 80 a 120 cm.';
            }

            if($height <= 200 || $height >= 250){
                $errors['height'] = 'Wysokość musi być między 200 a 250 cm.';
            }

            if(!$openingDirectionId){
                $errors['openingDirection'] = 'Wybierz kierunek otwierania drzwi.';
            }

            // Jeśli brak błędów, zapisz dane do sesji i przekieruj do następnego kroku
            if(empty($errors)){
                $currentData = $this->request->getSession('order') ?? [];
                
                $newData = [
                    'width' => $width,
                    'height' => $height,
                    'openingDirectionId' => $openingDirectionId,
                ];

                
                $order = array_merge($currentData, $newData);
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
        ]);
    }

    /**
     * Wyświetla widok wyboru modelu drzwi.
     *
     * @return void
     */
    public function model(): void {
        if($this->request->getMethod() === 'POST'){
            $currentData = $this->request->getSession('order') ?? [];
            $newData = [
                'color' => $this->request->getPost('door_color'),
                'type' => $this->request->getPost('type_id'),
            ];

            $order = array_merge($currentData, $newData);

            $this->request->setSession('order', $order);
            
            header('Location: /wyposazenie');
            exit();
        }

        $summaryData = $this->getSummaryData();

        $this->render('model', [
            'colors' => $this->doorRepository->getColors(),
            'types' => $this->doorRepository->getTypes(),
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
        ]);
    }

    /**
     * Wyświetla widok wyboru wyposażenia drzwi.
     *
     * @return void
     */

    public function equipment(): void {
        if($this->request->getMethod() === 'POST'){
            $currentData = $this->request->getSession('order') ?? [];
            $newData = [
                'accessories' => $this->request->getPost('accessories'),
            ];

            $order = array_merge($currentData, $newData);

            $this->request->setSession('order', $order);
            
            header('Location: /podsumowanie');
            exit();
        }
        $summaryData = $this->getSummaryData();
        $this->render('equipment', [
            'accessories' => $this->doorRepository->getAccessories(),
            'summaryData' => $summaryData['summaryDetails'],
            'summaryPrice' => $summaryData['summaryPrice'],
        ]);
    }

    /**
     * Wyświetla widok podsumowania zamówienia.
     *
     * @return void
     */
    public function summary(): void {
        $this->view->render([
            'page' => 'summary',
        ]);
    }
}