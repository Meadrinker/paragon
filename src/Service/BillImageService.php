<?php

namespace App\Service;

use App\Entity\Bill;
use Doctrine\ORM\EntityManagerInterface;

class BillImageService {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function createPath(Bill $bill, $imagesDirectory) {
        $userId = $bill->getUser()->getId();
        $id = $bill->getId();
        $path = DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $id;
        $fullPath = $imagesDirectory . $path;
        return $fullPath;
    }

    public function deleteImage(Bill $bill, $fullPath) {
        $currentImage = $bill->getImage();
        $currentImagePath = $fullPath . DIRECTORY_SEPARATOR . $currentImage;
        if (is_file($currentImagePath)) {
            unlink($currentImagePath);
        }
    }

}