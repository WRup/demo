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
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
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
            ->addSelect('u', 't')
            ->innerJoin('a.users', 'u')
            ->leftJoin('a.tags', 't')
//            ->where('p.publishedAt <= :now')
//            ->orderBy('a.name', 'ASC')
//            ->setParameter('now', new \DateTime())
        ;

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF a.tags')
                ->setParameter('tag', $tag);
        }

        $this->logger->info($qb->getQuery()->getDQL());

        return (new Paginator($qb))->paginate($page);
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
                ->orWhere('a.name LIKE :a_'.$key)
                ->setParameter('a_'.$key, '%'.$term.'%')
            ;
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