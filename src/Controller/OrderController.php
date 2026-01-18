<?php

namespace App\Controller;

use App\View;
use App\Core\Request;
use App\Model\DoorRepository;

class OrderController extends AbstractController
{
    private DoorRepository $doorRepository;
    public function __construct(Request $request, View $view, 
    ){
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

        if (isset($order['openingDirection'])) {
            $openingDirection = $this->doorRepository->getOpeningDirectionById($order['openingDirection']);
            $summaryData['openingDirection'] = $openingDirection ? $openingDirection['nazwa'] : null;
        }

        if (isset($order['color'])) {
            $color = $this->doorRepository->getColorById($order['color']);
            $summaryData['color'] = $color ? $color['kod_hex'] : null;
        }

        if (isset($order['type'])) {
            $type = $this->doorRepository->getTypeById($order['type']);
            $summaryData['type'] = $type ? $type['nazwa'] : null;
        }

        if (!empty($order['accessories'])) {
            $summaryData['accessories'] = $this->doorRepository->getAccessoryByIds($order['accessories']);
        }

        return $summaryData;
        
    }
    
    /**
     * Wyświetla widok dla wymiarów.
     *
     * @return void
     */
    public function dimensions(): void
    {
        if($this->request->getMethod() === 'POST'){
            $currentData = $this->request->getSession('order') ?? [];

            $newData = [
                'width' => $this->request->getPost('width'),
                'height' => $this->request->getPost('height'),
                'openingDirection' => $this->request->getPost('opening_direction_id'),
            ];

            $order = array_merge($currentData, $newData);

            $this->request->setSession('order', $order);
            
            header('Location: /model');
            exit();
        }

        $this->render(
            'dimensions',
            [
                'openingDirections' => $this->doorRepository->getOpeningDirection(),
                'summaryData' => $this->getSummaryData(),
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

        $this->render('model', [
            'colors' => $this->doorRepository->getColors(),
            'types' => $this->doorRepository->getTypes(),
            'summaryData' => $this->getSummaryData(),
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

        $this->render('equipment', [
            'accessories' => $this->doorRepository->getAccessories(),
            'summaryData' => $this->getSummaryData(),
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