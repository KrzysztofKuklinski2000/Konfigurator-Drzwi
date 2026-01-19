<?php

namespace App\Service;
use App\Core\Request;
use App\Model\DoorRepository;

class OrderService {
    public function __construct(private Request $request, private DoorRepository $doorRepository){}

    /** 
    * Pobieranie Szczegółow zamówienia
    *
    * @return array
    */
    public function getSummaryData(): array {
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

    public function saveOrder(array $order): int {

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

        $orderId = $this->doorRepository->saveOrder(
            $order, 
            $finalePrice, 
            $deliveryPrice, 
            $productsPrice
        );

        $this->request->setSession('order', []);

        return $orderId;
    }

    public function checkAccess(): void {
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
    }

    public function getDeliveryDetails(?int $methodId): array
    {
        $default = ['price' => 0, 'name' => 'Nie wybrano'];

        if (!$methodId) {
            return $default;
        }

        $methods = $this->doorRepository->getDeliveryMethods();

        foreach ($methods as $method) {
            if ($method['id'] === $methodId) {
                return [
                    'price' => (float) $method['cena'],
                    'name'  => $method['nazwa']
                ];
            }
        }
        
        return $default;
    }
}