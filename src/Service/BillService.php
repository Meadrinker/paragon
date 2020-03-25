<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\FileBag;

class BillService {

    protected $em;
    private $imageService;

    public function __construct(EntityManagerInterface $em, BillImageService $imageService) {
        $this->em = $em;
        $this->imageService = $imageService;
    }

    public function getBills($id) {
        $billEntity = $this->em->getRepository(Bill::class)->findBy(['user' => $id]);
        $bills = [];
        foreach ($billEntity as $bill) {
            $bills[$bill->getId()] = [
                'user' => $bill->getUser()->getName(),
                'image' => $bill->getImage(),
                'shop' => $bill->getShop(),
                'date' => $bill->getDate()
            ];
            $billId = $bill->getId();
            $productEntity = $this->em->getRepository(Product::class)->findBy(['bill' => $billId]);
            foreach ($productEntity as $product) {
                $bills[$bill->getId()][$product->getId()] = [
//                    'image' => $product->getImages(),
                    'name' => $product->getName(),
                    'guarantee' => $product->getGuarantee(),
                    'type' => $product->getType(),
                    'price' => $product->getPrice()
                ];
                $productId = $product->getId();
                $productImageEntity = $this->em->getRepository(ProductImage::class)->findBy(['product' => $productId]);
                foreach ($productImageEntity as $productImage) {
                    $bills[$bill->getId()][$product->getId()][$productImage->getId()] = [
                      'image' => $productImage->getImage()
                    ];
                }
            }
        }
        return $bills;
    }

    public function getBill($id) {
        $billEntity = $this->em->getRepository(Bill::class)->findOneBy(['id' => $id]);
        $bill = [
            'user' => $billEntity->getUser()->getName(),
            'image' => $billEntity->getImage(),
            'shop' => $billEntity->getShop(),
            'date' => $billEntity->getDate()
        ];
        $productEntity = $this->em->getRepository(Product::class)->findBy(['bill' => $id]);
            foreach ($productEntity as $product) {
                $bill[$product->getId()] = [
                    'image' => $product->getImage(),
                    'name' => $product->getName(),
                    'guarantee' => $product->getGuarantee(),
                    'type' => $product->getType(),
                    'price' => $product->getPrice()
                ];
            }
        return $bill;
    }


    public function deleteBill($id) {
        $this->em->getRepository(Bill::class)->deleteBill($id);
        $delete[] = [
            'message' => 'usunieto paragon o id ' . $id
        ];
        return $delete;
    }

    public function addBill($params, FileBag $files, $imagesDirectory) {
        $bill = new Bill();
        $bill->setUser($this->em->getReference(User::class, $params['user']));
        $bill->setName($params['name']);
        $bill->setDate($params['date']);
        $bill->setShop($params['shop']);
        $this->em->getConnection()->beginTransaction();
        try {
            $file = $files->get('image');
            $fileExtension = $file->guessExtension();
            $fileName = $this->generateUniqueFileName().'.'.$fileExtension;
            $bill->setImage($fileName);
            $this->em->persist($bill);
            $this->em->flush();
            $fullPath = $this->imageService->createPath($bill, $imagesDirectory);
            $this->createFolder($fullPath);
            $file->move($fullPath, $fileName);
            $this->em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $this->em->getConnection()->rollBack();
        }
    }

    public function updateBill($id, $params, FileBag $files, $imagesDirectory) {
        $bill = $this->em->getRepository(Bill::class)->find($id);
        $bill->setName($params['name']);
        $bill->setDate($params['date']);
        $bill->setShop($params['shop']);
        $this->em->getConnection()->beginTransaction();
        try {
            $fullPath = $this->imageService->createPath($bill, $imagesDirectory);
            $this->createFolder($fullPath);
            $this->imageService->deleteImage($bill, $fullPath);
            $file = $files->get('image');
            $fileExtension = $file->guessExtension();
            $fileName = $this->generateUniqueFileName().'.'.$fileExtension;
            $bill->setImage($fileName);
            $this->em->persist($bill);
            $this->em->flush();
            $file->move($fullPath, $fileName);
            $this->em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $this->em->getConnection()->rollBack();
        }
    }

    private function generateUniqueFileName() {
        return md5(uniqid());
    }

    private function createFolder($path) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }


}