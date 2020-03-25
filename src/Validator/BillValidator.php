<?php

namespace App\Validator;

use DateTime;
use Symfony\Component\HttpFoundation\FileBag;

class BillValidator {

    private $errors = [];

    public function valid($params, FileBag $files) {
        $validatedDate = $this->validateDate($params['date']);
        if ($validatedDate == false) {
            $this->errors[] = ['field' => 'date', 'message' => 'nieprawidlowa forma zapisu daty lub godziny, poprawny zapis: d-m-Y H:i:s'];
        }
        $validatedShop = $this->validateShop($params['shop']);
        if ($validatedShop == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy sklepu jest nieodpowiednia'];
        }
        $validatedName = $this->validateName($params['name']);
        if ($validatedName == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy paragonu jest nieodpowiednia'];
        }
        $validatedImage = $this->validateImage($files->get('image'));
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

    private function validateName($name) {
        if (strlen($name) <= 30 && strlen($name) >= 1) {
            return true;
        } else {
            return false;
        }
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
        $imageExtension = $image->guessExtension();
        if(array_search($imageExtension, $extensionAllowed) === false) {
            return false;
        } else {
            return true;
        }
    }

}