<?php

namespace App\Repository;

use App\Entity\Accessory;
use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

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

    /**
     * @return integer
     */
    public function findLoansByAccessoryIdCount(Accessory $accessory): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from("App:Loan", "l")
            ->andWhere("l.accessory = :accessory")
            ->setParameter('accessory', $accessory)
            ->addSelect('COUNT(l)')
//            ->innerJoin('l.accessory', 'u')
//            ->leftJoin('a.tags', 't')
//            ->where('p.publishedAt <= :now')
//            ->orderBy('a.name', 'ASC')
//            ->setParameter('now', new \DateTime())
        ;

//        if (null !== $tag) {
//            $qb->andWhere(':tag MEMBER OF a.tags')
//                ->setParameter('tag', $tag);
//        }
//        var_dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getSingleScalarResult();
//        $this->logger->info($qb->getQuery()->getDQL());

//        return (new Paginator($qb))->paginate($page);
    }
}