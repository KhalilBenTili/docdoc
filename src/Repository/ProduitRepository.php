<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findProduitByCat($categorie){
        return $this->createQueryBuilder('p')
            ->where('p.categorie LIKE :categorie')
            ->setParameter('categorie', '%'.$categorie.'%')
            ->getQuery()->getResult();


    }


    public function findProduitByRef($reference){
        return $this->createQueryBuilder('produit')
            ->where('produit.reference LIKE :reference')
            ->setParameter('reference', '%'.$reference.'%')
            ->getQuery()
            ->getResult();
    }



    public function OrderByRefC()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.reference','ASC')
            ->getQuery()->getResult()
            ;

    }
    public function OrderByRefD()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.reference','DESC')
            ->getQuery()->getResult()
            ;

    }
    public function OrderByPrixC()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.prix','ASC')
            ->getQuery()->getResult()
            ;

    }
    public function OrderByPrixD()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.prix','DESC')
            ->getQuery()->getResult()
            ;

    }
    public function OrderByQC()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.quantite','ASC')
            ->getQuery()->getResult()
            ;

    }
    public function OrderByQD()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.quantite','DESC')
            ->getQuery()->getResult()
            ;

    }


}
