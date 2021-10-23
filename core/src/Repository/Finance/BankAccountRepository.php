<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\BankAccount;
use App\Exception\Finance\BankAccountException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BankAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccount::class);
    }

    /**
     * @throws BankAccountException
     */
    public function getBankAccountById(int $bankAccountId): BankAccount
    {
        $bankAccount = $this->findBankAccountById($bankAccountId);
        if (null === $bankAccount) {
            throw new BankAccountException(BankAccountException::NOT_FOUND, ['reason' => 'No bank account with ' . $bankAccountId . ' found']);
        }

        return $bankAccount;
    }

    public function findBankAccountById(int $bankAccountId): ?BankAccount
    {
        return $this->getEntityManager()
            ->getRepository(BankAccount::class)
            ->find($bankAccountId);
    }
}
