<?php

namespace App\Repository;

use App\Entity\LivreGolden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivreGolden|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivreGolden|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivreGolden[]    findAll()
 * @method LivreGolden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreGoldenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivreGolden::class);
    }

    // /**
    //  * @return LivreGolden[] Returns an array of LivreGolden objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivreGolden
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
