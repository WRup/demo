<?php

namespace App\Repository;

use App\Entity\Accessory;
use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository that contains methods which guarantees access to Loans information.
 */
class LoanRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    /**
     * @return Loan | Collection
     */
    public function findLoansByAccessoryId(Accessory $accessory): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where("l.accessory = :accessory")
            ->setParameter("accessory", $accessory);

        return $qb->getQuery()->getResult();
    }

    public function findLoansByAccessoryIdCount(Accessory $accessory): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from("App:Loan", "l")
            ->andWhere("l.accessory = :accessory")
            ->setParameter('accessory', $accessory)
            ->addSelect('COUNT(l)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}