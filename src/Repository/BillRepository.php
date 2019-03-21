<?php

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\ORM\EntityRepository;

class BillRepository extends EntityRepository {

    public function deleteBill($id) {
        $results = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete(Bill::class, 'b')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $results;
    }
}