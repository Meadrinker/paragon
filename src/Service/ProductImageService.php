<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductImageService {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function createPath(Product $product, $imagesDirectory) {
        $userId = $product->getBill()->getUser()->getId();
        $billId = $product->getBill()->getId();
        $id = $product->getId();
        $path = DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $billId . DIRECTORY_SEPARATOR . $id;
        $fullPath = $imagesDirectory . $path;
        return $fullPath;
    }

    public function deleteImage(Product $bill, $fullPath) {
        $currentImage = $bill->getImage();
        $currentImagePath = $fullPath . DIRECTORY_SEPARATOR . $currentImage;
        if (is_file($currentImagePath)) {
            unlink($currentImagePath);
        }
    }

}