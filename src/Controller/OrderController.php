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
    public function dimensions()
    {
        $this->view->render(
            [
                'page' => 'dimensions',
                'openingDirections' => $this->doorRepository->getOpeningDirection(),
            ]
        );
    }
}