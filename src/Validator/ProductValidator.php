<?php

namespace App\Validator;

use DateTime;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProductValidator {

    private $errors = [];

    public function validWithoutImage($params) {
        $validatedName = $this->validateName($params['name']);
        if ($validatedName == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy produktu jest nieodpowiednia'];
        }
        $validatedType = $this->validateType($params['type']);
        if ($validatedType == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy typu produktu jest nieodpowiednia'];
        }
        $validatedGuarantee = $this->validateGuarantee($params['guarantee']);
        if ($validatedGuarantee == false) {
            $this->errors[] = ['field' => 'nieprawidlowa forma zapisu okresu gwarancji'];
        }
        $validatedPrice = $this->validatePrice($params['price']);
        if ($validatedPrice == false) {
            $this->errors[] = ['field' => 'nieprawidlowa forma zapisu ceny'];
        }

        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function valid($params, FileBag $files) {
        $validatedName = $this->validateName($params['name']);
        if ($validatedName == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy produktu jest nieodpowiednia'];
        }
        $validatedType = $this->validateType($params['type']);
        if ($validatedType == false) {
            $this->errors[] = ['field' => 'dlugosc nazwy typu produktu jest nieodpowiednia'];
        }
        $validatedGuarantee = $this->validateGuarantee($params['guarantee']);
        if ($validatedGuarantee == false) {
            $this->errors[] = ['field' => 'nieprawidlowa forma zapisu okresu gwarancji'];
        }
        $validatedImage = $this->validateImage($files->get('image'));
        if ($validatedImage == false) {
            $this->errors[] = ['field' => 'image', 'code' => 'wrong.extension'];
        }
        $validatedPrice = $this->validatePrice($params['price']);
        if ($validatedPrice == false) {
            $this->errors[] = ['field' => 'nieprawidlowa forma zapisu ceny'];
        }

        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function getErrors() {
        return $this->errors;
    }


    private function validateName($name) {
        if (strlen($name) <= 64 && strlen($name) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    private function validateType($type) {
        if (strlen($type) <= 64 && strlen($type) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    private function validateGuarantee($guarantee) {
        if (strlen($guarantee) <= 30 && strlen($guarantee) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    private function validateImage($images) {
        $extensionAllowed = [
            'jpeg',
            'jpg',
            'png'
        ];
        $boolean = [];
        foreach ($images as $image) {
            $imageExtension = $image->guessExtension();
            if(array_search($imageExtension, $extensionAllowed) === false) {
                $boolean = 'false';
            } else {
                $boolean = 'true';
            }
        }
        if (in_array('false', $boolean)) {
            return false;
        } else {
            return true;
        }

    }

    private function validatePrice($price) {
        if (preg_match('/^\d+(\.\d{2})?$/', $price)) {
            return true;
        } else {
            return false;
        }
    }

}