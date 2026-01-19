<?php 

namespace App\Core;


class Validator {


    public function validateDimensions(array $data): ?array {
        $errors = [];
        if( $data['width'] <= 80 ||  $data['width'] >= 120) {
            $errors['width'] = 'Szerokość musi być między 80 a 120 cm.';
        }

        if($data['height'] <= 200 || $data['height'] >= 250){
            $errors['height'] = 'Wysokość musi być między 200 a 250 cm.';
        }

        if(!$data['openingDirectionId']){
            $errors['openingDirection'] = 'Wybierz kierunek otwierania drzwi.';
        }

        return $errors;
    } 

    public function validateModel(array $data): ?array {
        $errors = [];

        if(!$data['colorId']) {
            $errors['color'] = "Wybierz kolor drzwi";
        }

        if(!$data['typeId']) {
            $errors['type'] = "Wybierz typ drzwi";
        }

        return $errors;
    }

    public function validateUserData(array $data): ?array {
        if(!$data['deliveryMethodId']) {
            $errors['deliveryMethodId'] = 'Wybierz metodę dostawy';
        }

        if(!$data['firstName']) {
            $errors['firstName'] = 'Podaj imię';
        }

        if(!$data['lastName'])  {
            $errors['lastName'] = 'Podaj nazwisko';
        }

        if(!$data['email']) {
            $errors['email'] = 'Podaj email';
        }

        if(!$data['phone']) {
            $errors['phone'] = 'Podaj telefon';
        }

        if(!$data['address']) {
            $errors['address'] = 'Podaj adres';
        }

        if(!$data['postalCode']){
            $errors['postalCode'] = 'Podaj kod pocztowy';
        }

        if(!$data['city']) {
            $errors['city'] = 'Podaj miasto';
        }

        return $errors;
    }
}