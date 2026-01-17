<?php

namespace App\Controller;

use App\Model\DoorRepository;
use App\View;

class OrderController extends AbstractController
{
    private DoorRepository $doorRepository;
    public function __construct(
        private View $view
    ){
        parent::__construct();

        // Inicjalizacja repozytorium drzwi z połączeniem do bazy danych
        $this->doorRepository = new DoorRepository($this->getDbConnection());
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

        $this->view->render(
            [
                'page' => 'dimensions',
                'openingDirections' => $this->doorRepository->getOpeningDirection(),
            ]
        );
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

        $this->view->render([
            'page' => 'model',
            'colors' => $this->doorRepository->getColors(),
            'types' => $this->doorRepository->getTypes(),
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

        $this->view->render([
            'page' => 'equipment',
            'accessories' => $this->doorRepository->getAccessories(),
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
            'order' => $this->request->getSession('order'),
        ]);
    }
}