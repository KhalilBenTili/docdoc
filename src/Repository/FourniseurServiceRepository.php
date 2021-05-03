<?php

namespace App\Repository;

use App\Entity\FourniseurService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FourniseurService|null find($id, $lockMode = null, $lockVersion = null)
 * @method FourniseurService|null findOneBy(array $criteria, array $orderBy = null)
 * @method FourniseurService[]    findAll()
 * @method FourniseurService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FourniseurServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FourniseurService::class);
    }

    // /**
    //  * @return FourniseurService[] Returns an array of FourniseurService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FourniseurService
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function fournisseurs_avec_categorie($categorie){
        return $this->createQueryBuilder('f')
            ->where('f.categorie==?cat')
            ->orderBy('f.gouvernorat')
            ->getQuery()
            ->getResult();
    }
    public function searchFournniseur($var)
    {
        return $this->createQueryBuilder('f')
            ->where('f.fourniseur Like :var')
            ->setParameter('var', '%'.$var.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function fournisseurParServiceLibelle($value){
        return $this->createQueryBuilder('f')
            ->leftJoin('f.service','s')
            ->addSelect('s')
            ->where('s.id like :val')
            ->setParameter('val',$value)
            ->getQuery()
            ->getResult();
    }

}
