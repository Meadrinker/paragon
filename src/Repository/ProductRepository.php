<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository {

    public function deleteProduct($id) {
        $results = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete(Product::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $results;
    }
}