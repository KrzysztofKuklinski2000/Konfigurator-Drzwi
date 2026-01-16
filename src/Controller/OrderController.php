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
    public function dimensions()
    {
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
        $this->view->render([
            'page' => 'equipment',
            'accessories' => $this->doorRepository->getAccessories(),
        ]);
    }
}