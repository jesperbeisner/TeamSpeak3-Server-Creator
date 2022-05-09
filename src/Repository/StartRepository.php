<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Start;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Start|null find($id, $lockMode = null, $lockVersion = null)
 * @method Start|null findOneBy(array $criteria, array $orderBy = null)
 * @method Start[]    findAll()
 * @method Start[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Start::class);
    }

    public function findLastCreatedStart(): ?Start
    {
        return $this->createQueryBuilder('s')
            ->setMaxResults(1)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
