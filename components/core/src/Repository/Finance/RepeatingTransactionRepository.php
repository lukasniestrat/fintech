<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\RepeatingTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RepeatingTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepeatingTransaction::class);
    }
}
