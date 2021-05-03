<?php

namespace App\Repository;

use App\Entity\CategorieMedicale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieMedicale|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieMedicale|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieMedicale[]    findAll()
 * @method CategorieMedicale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieMedicaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieMedicale::class);
    }

    // /**
    //  * @return CategorieMedicale[] Returns an array of CategorieMedicale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieMedicale
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
