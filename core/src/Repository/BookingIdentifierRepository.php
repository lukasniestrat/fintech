<?php

namespace App\Repository;

use App\Entity\BookingIdentifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookingIdentifier|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingIdentifier|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingIdentifier[]    findAll()
 * @method BookingIdentifier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingIdentifierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingIdentifier::class);
    }

    // /**
    //  * @return BookingIdentifier[] Returns an array of BookingIdentifier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookingIdentifier
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
