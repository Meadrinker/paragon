<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProductService {

    protected $em;
    private $imageService;

    public function __construct(EntityManagerInterface $em, ProductImageService $imageService) {
        $this->em = $em;
        $this->imageService = $imageService;
    }

    public function getProducts($id) {
        $billEntity = $this->em->getRepository(Bill::class)->findBy(['user' => $id]);
        $products = [];
        foreach ($billEntity as $bill) {
            $billId = $bill->getId();
            $productEntity = $this->em->getRepository(Product::class)->findBy(['bill' => $billId]);
            foreach ($productEntity as $product) {
                $products[$product->getId()] = [
                    'image' => $product->getImage(),
                    'name' => $product->getName(),
                    'guarantee' => $product->getGuarantee(),
                    'type' => $product->getType(),
                    'price' => $product->getPrice()
                ];
            }
        }
        return $products;
    }


    public function deleteProduct($id) {
        $this->em->getRepository(Product::class)->deleteProduct($id);
        $delete[] = [
            'message' => 'usunieto produkt o id ' . $id
        ];
        return $delete;
    }

    public function addProduct($id, $params, FileBag $files, $imagesDirectory) {
        $this->em->getConnection()->beginTransaction();
        try {
            $product = new Product();
            $product->setBill($this->em->getReference(Bill::class, $id));
            $product->setName($params['name']);
            $product->setType($params['type']);
            $product->setGuarantee($params['guarantee']);
            $product->setPrice($params['price']);
            foreach ($files as $file) {
                $productImage = new ProductImage();
                $productImage->setProduct($product);
                $fileExtension = $file->guessExtension();
                $fileName = $this->generateUniqueFileName().'.'.$fileExtension;
                $productImage->setImage($fileName);
                $product->addImage($productImage);
                $this->em->persist($product);
                $this->em->flush();
                $fullPath = $this->imageService->createPath($product, $imagesDirectory);
                $this->createFolder($fullPath);
                $file->move($fullPath, $fileName);
            }
            $this->em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $this->em->getConnection()->rollBack();
        }
    }

    public function updateProduct($id, $params) {
        $this->em->getConnection()->beginTransaction();
        try {
            $product = $this->em->getRepository(Product::class)->find($id);
            $product->setName($params['name']);
            $product->setType($params['type']);
            $product->setGuarantee($params['guarantee']);
            $product->setPrice($params['price']);
            $this->em->persist($product);
            $this->em->flush();
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