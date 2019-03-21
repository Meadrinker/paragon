<?php

namespace App\Validator;

use DateTime;
use Symfony\Component\HttpFoundation\ParameterBag;

class BillValidator {

    private $errors = [];

    public function valid(ParameterBag $params) {
        $validatedDate = $this->validateDate($params->get('date'));
        if ($validatedDate == false) {
            $this->errors[] = ['field' => 'date', 'message' => 'nieprawidlowa forma zapisu daty lub godziny, poprawny zapis: d-m-Y H:i:s'];
        }
        $validatedShop = $this->validateShop($params->get('shop'));
        if ($validatedShop == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy sklepu jest nieodpowiednia'];
        }
        $validatedImage = $this->validateImage($params->get('image'));

        if ($validatedImage == false) {
            $this->errors[] = ['field' => 'image', 'code' => 'wrong.extension'];
        }

        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function getErrors() {
        return $this->errors;
    }

    private function validateDate($date, $format = 'd-m-Y H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private function validateShop($shop) {
        if (strlen($shop) <= 30 && strlen($shop) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    private function validateImage($image) {
        $extensionAllowed = [
            'jpeg',
            'jpg',
            'png'
        ];
        $imageExploded = explode('.', $image);
        $imageExtension = $imageExploded[1];
        if(array_search($imageExtension, $extensionAllowed) === false) {
            return false;
        } else {
            return true;
        }
    }

}