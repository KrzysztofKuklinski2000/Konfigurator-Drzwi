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
        $this->view->render([
            'page' => 'summary',
        ]);
    }
}