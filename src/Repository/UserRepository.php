<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * This custom Doctrine repository is empty because so far we don't need any custom
 * method to query for application user information. But it's always a good practice
 * to define a custom repository that will be used when the application grows.
 *
 * See https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class UserRepository extends ServiceEntityRepository
{

    private $logger;
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, User::class);
        $this->logger = $logger;
    }


    public function findAllStudentUsersPaginator(int $page = 1): Paginator
    {

        $studentRoles = "ROLE_USER";
        $qb = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder = $this->createQueryBuilder('u')
            ->where($qb->expr()->like('u.roles', ':studentRoles'))
            ->setParameter('studentRoles', '%' . $studentRoles . '%')
        ;



        $this->logger->info($queryBuilder->getQuery()->getDQL());

        return (new Paginator($queryBuilder))->paginate($page);
    }

    public function findAllStudentUsers(): array
    {

        $studentRoles = "ROLE_USER";
        $qb = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder = $this->createQueryBuilder('u')
            ->where($qb->expr()->like('u.roles', ':studentRoles'))
            ->setParameter('studentRoles', '%' . $studentRoles . '%')
        ;



        $this->logger->info($queryBuilder->getQuery()->getDQL());

        return $queryBuilder->getQuery()->getResult();
    }
}
