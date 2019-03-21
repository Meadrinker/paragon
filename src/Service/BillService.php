<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class BillService {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
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
                    'image' => $product->getImage(),
                    'name' => $product->getName(),
                    'guarantee' => $product->getGuarantee(),
                    'type' => $product->getType(),
                    'price' => $product->getPrice()
                ];
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

    public function addBill(ParameterBag $params) {
        $bill = new Bill();
        $bill->setUser($this->em->getReference(User::class, $params->get('user')));
        $bill->setDate($params->get('date'));
        $bill->setImage($params->get('image'));
        $bill->setShop($params->get('shop'));
        $this->em->persist($bill);
        $this->em->flush();
    }

    public function updateBill($id, ParameterBag $params) {
        $bill = $this->em->getRepository(Bill::class)->find($id);
        $bill->setDate($params->get('date'));
        $bill->setImage($params->get('image'));
        $bill->setShop($params->get('shop'));
        $this->em->persist($bill);
        $this->em->flush();
    }

}