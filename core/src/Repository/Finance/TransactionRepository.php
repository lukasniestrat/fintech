<?php
declare(strict_types=1);
namespace App\Repository\Finance;

use App\Entity\Finance\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function storeTransaction(Transaction $transaction): Transaction
    {
        $this->getEntityManager()->persist($transaction);
        $this->getEntityManager()->flush();

        return $transaction;
    }

    public function findTransactionsBySubject(string $subject): ?array
    {
        $result = $this->getEntityManager()
            ->getRepository(Transaction::class)
            ->findBy(['subject' => $subject], null, 1000);

        if (0 === count($result)) {
            return null;
        }

        return $result;
    }
}
