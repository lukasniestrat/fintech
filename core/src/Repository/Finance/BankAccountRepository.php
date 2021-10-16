<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\BankAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BankAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccount::class);
    }

    public function findBankAccountById(int $bankAccountId): ?BankAccount
    {
        return $this->getEntityManager()
            ->getRepository(BankAccount::class)
            ->find($bankAccountId);
    }
}
