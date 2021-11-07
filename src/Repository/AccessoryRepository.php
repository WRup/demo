<?php

namespace App\Repository;

use App\Entity\Accessory;
use App\Entity\Tag;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use function Symfony\Component\String\u;

/**
 * Repository that contains methods which guarantees access to Accessory information.
 */
class AccessoryRepository extends ServiceEntityRepository
{

    private $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Accessory::class);
        $this->logger = $logger;
    }

    public function findLatest(int $page = 1, Tag $tag = null): Paginator
    {

        $qb = $this->createQueryBuilder('a')
            ->addSelect('l', 't')
            ->leftJoin('a.loans', 'l')
            ->leftJoin('a.tags', 't');

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF a.tags')
                ->setParameter('tag', $tag);
        }

        $this->logger->info($qb->getQuery()->getDQL());

        return (new Paginator($qb->orderBy('a.name', 'ASC')))->paginate($page);
    }

    public function findAll(int $page = 1): Paginator
    {

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC');

        $this->logger->info($qb->getQuery()->getDQL());

        return (new Paginator($qb))->paginate($page);
    }

    public function findLoanedAccessoriesById(int $id): int
    {

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from("App:Accessory", "a")
            ->andWhere("a.id = :id")
            ->setParameter('id', $id)
            ->addSelect('COUNT(u)')
            ->innerJoin('a.users', 'u');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Accessory[]
     */
    public function findBySearchQuery(string $query, int $limit = Paginator::PAGE_SIZE): array
    {
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('a');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('a.name LIKE :a_' . $key)
                ->setParameter('a_' . $key, '%' . $term . '%');
        }

        return $queryBuilder
            ->orderBy('a.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Transforms the search string into an array of search terms.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, static function ($term) {
            return 2 <= $term->length();
        });
    }
}