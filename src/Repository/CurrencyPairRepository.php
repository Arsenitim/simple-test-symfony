<?php

namespace App\Repository;

use App\Entity\CurrencyPair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyPair>
 */
class CurrencyPairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyPair::class);
    }

    public function findOneByBaseAndQuote(string $currencyBase, string $currencyQuote): ?CurrencyPair
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.currencyBase = :cb')
            ->setParameter('cb', $currencyBase)
            ->andWhere('c.currencyQuote = :cq')
            ->setParameter('cq', $currencyQuote)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
