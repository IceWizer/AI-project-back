<?php

namespace App\Repository;

use App\Entity\Candle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Candle>
 */
class CandleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candle::class);
    }

    /**
     * @return Candle[] Returns an array of Candle objects
     */
    public function findActiveCandleByFilter(string $title = null, Uuid $categoryId = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.active = :active')
            ->setParameter('active', true);

        if ($categoryId !== null) {
            $qb->leftJoin('c.categories', 'cat')
                ->andWhere('cat.id = :categoryId')
                ->setParameter('categoryId', $categoryId, UuidType::NAME);
        }

        if ($title !== null) {
            $qb->andWhere('c.title LIKE :title')
                ->setParameter('title', "%$title%");
        }

        return $qb->getQuery()->getResult();
    }

    public function findByIdIn(array $ids): array
    {
        $qb = new QueryBuilder($this->getEntityManager());

        return $this->createQueryBuilder('c')
            ->where($qb->expr()->in('c.id', ':ids'))
            ->setParameter('ids', array_map(fn(string $id) => Uuid::fromString($id)->toBinary(), $ids))
            ->getQuery()
            ->getResult();
    }
}
