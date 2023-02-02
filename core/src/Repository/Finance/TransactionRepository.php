<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\Transaction;
use App\Exception\Finance\TransactionException;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function removeTransaction(Transaction $transaction): void
    {
        $this->getEntityManager()->remove($transaction);
        $this->getEntityManager()->flush();
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

    /**
     * @throws TransactionException
     */
    public function getTransactionById(int $id): Transaction
    {
        $transaction = $this->findTransactionById($id);

        if (null === $transaction) {
            throw new TransactionException(TransactionException::NOT_FOUND, ['reason' => sprintf('no transaction with id %s found', $id)]);
        }

        return $transaction;
    }

    public function findTransactionById(int $id): ?Transaction
    {
        return $this->getEntityManager()
            ->getRepository(Transaction::class)
            ->find($id);
    }

    public function getTransactions(RequestMetaData $requestMetaData): ModelList
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('transaction')
            ->from(Transaction::class, 'transaction')
            ->setFirstResult($requestMetaData->getOffset())
            ->setMaxResults($requestMetaData->getLimit())
            ->addOrderBy('transaction.' . $requestMetaData->getOrderBy(), $requestMetaData->getOrderSequence())
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_OBJECT);

        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        $result = $paginator->getIterator()->getArrayCopy();
        $requestMetaData->setTotal($paginator->count());

        return new ModelList($result, $requestMetaData);
    }
}
