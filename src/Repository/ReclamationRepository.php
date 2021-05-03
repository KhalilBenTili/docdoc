<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findReclamationBymotif($rec){
        return $this->createQueryBuilder('reclamation')
            ->where('reclamation.motif LIKE :motif')
            ->setParameter('motif', '%'.$rec.'%')
            ->getQuery()
            ->getResult();
    }

       public function OrderByMotif(){
           $em=$this->getEntityManager();
           $query=$em->createQuery('select r from App\Entity\Reclamation r order by r.motif ASC');
           return $query->getResult();
       }
       function listReclamationByUser($id){
           return $this->createQueryBuilder('r')
           ->join('r.user','u')
               ->addSelect('u')
               ->where('u.id=:id')
               ->setParameter('id',$id)
               ->getQuery()->getResult();

       }
   /*    public function findReclamationParMotif($reclamation){
           return $this->createQueryBuilder('reclamation')
               ->where('reclamation.motif LIKE :motif')
               ->setParameter('title', '%'.$reclamation.'%')
               ->getQuery()
               ->getResult();
       }*/
}
