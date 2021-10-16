<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\BookingIdentifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookingIdentifierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingIdentifier::class);
    }
}
