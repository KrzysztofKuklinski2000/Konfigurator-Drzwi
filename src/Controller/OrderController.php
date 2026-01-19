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
            $order = array_merge($order, $formData);

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
        $deliveryData = $this->orderService->getDeliveryDetails($order['deliveryMethodId'] ?? null);
        
        $html = $this->view->renderToString('pdf/offer', [
            'summaryDetails' => $summaryData['summaryDetails'],
            'productsPrice' => $summaryData['summaryPrice'],
            'deliveryName' => $deliveryData['name'],
            'deliveryPrice' =>  $deliveryData['price'],
            'totalPrice' =>  $summaryData['summaryPrice'] + $deliveryData['price'],
        ]);

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