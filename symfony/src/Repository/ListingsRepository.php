<?php

namespace App\Repository;

use App\Entity\Listings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Listings>
 */
class ListingsRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Listings::class);
        $this->paginator = $paginator;
    }

    private function createSearchQueryBuilder(string $search)
    {
        return $this->createQueryBuilder('l')
            ->where('LOWER(l.title) LIKE LOWER(:val)')
            ->orWhere('LOWER(l.company) LIKE LOWER(:val)')
            ->orWhere('LOWER(l.description) LIKE LOWER(:val)')
            ->orWhere('LOWER(l.tags) LIKE LOWER(:val)')
            ->setParameter('val', '%' . strtolower($search) . '%')
            ->orderBy('l.createdAt', 'ASC');
    }
    private function createTitleQueryBuilder(string $search)
    {
        return $this->createQueryBuilder('l')
            ->where('LOWER(l.title) LIKE LOWER(:val)')
            ->setParameter('val', '%' . strtolower($search) . '%')
            ->orderBy('l.createdAt', 'ASC');
    }

    private function createTagsQueryBuilder(string $tag)
    {
        return $this->createQueryBuilder('l')
            ->where('LOWER(l.tags) LIKE LOWER(:val)')
            ->setParameter('val', '%' . strtolower($tag) . '%')
            ->orderBy('l.createdAt', 'ASC');
    }

    public function getListings_Search_List(string $search): array
    {
        return $this->createSearchQueryBuilder($search)
            ->getQuery()
            ->getResult();
    }

    public function getListings_tags_List(string $tag): array
    {
        return $this->createTagsQueryBuilder($tag)
            ->getQuery()
            ->getResult();
    }
    public function getListings_title_List(string $tag): array
    {
        return $this->createTitleQueryBuilder($tag)
            ->getQuery()
            ->getResult();
    }

    private function paginate($queryBuilder, int $page, int $limit)
    {
        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }

    public function getfindall_pagination(int $page = 1, int $limit = 6)
    {
        return $this->paginate($this->createQueryBuilder('l'), $page, $limit);
    }

    public function getListings_Search_pagination(int $page = 1, int $limit = 6, string $search)
    {
        return $this->paginate($this->createSearchQueryBuilder($search), $page, $limit);
    }

    public function getListings_tag_pagination(int $page = 1, int $limit = 6, string $tag)
    {
        return $this->paginate($this->createTagsQueryBuilder($tag), $page, $limit);
    }
}